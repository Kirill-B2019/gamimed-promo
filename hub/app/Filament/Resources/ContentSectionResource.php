<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
use App\Filament\Resources\ContentSectionResource\Pages;
use App\Models\ContentSection;
use App\Models\Site;
use App\Models\SiteGroup;
use App\Services\AdminScope;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContentSectionResource extends Resource
{
    protected static ?string $model = ContentSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Content section';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('scope_type')
                    ->label('Scope')
                    ->options([
                        'global' => 'Global (all sites)',
                        'group' => 'Group',
                        'site' => 'Site override',
                    ])
                    ->required()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (Forms\Components\Select $component, ?ContentSection $record) {
                        if (! $record) {
                            $component->state('global');

                            return;
                        }

                        $component->state(match (true) {
                            $record->isSiteOverride() => 'site',
                            $record->isGroupScoped() => 'group',
                            default => 'global',
                        });
                    }),
                Forms\Components\Select::make('site_group_id')
                    ->label('Group')
                    ->options(fn () => SiteGroup::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->visible(fn (Forms\Get $get) => $get('scope_type') === 'group')
                    ->required(fn (Forms\Get $get) => $get('scope_type') === 'group')
                    ->native(false),
                Forms\Components\Select::make('site_id')
                    ->label('Site')
                    ->options(fn () => Site::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->visible(fn (Forms\Get $get) => $get('scope_type') === 'site')
                    ->required(fn (Forms\Get $get) => $get('scope_type') === 'site')
                    ->native(false),
                Forms\Components\Select::make('section_key')
                    ->label('Section')
                    ->options([
                        'hero' => 'Hero',
                        'about' => 'About',
                        'tokenomics' => 'Tokenomics',
                        'roadmap' => 'Roadmap',
                        'team' => 'Team',
                        'partners' => 'Partners',
                        'security' => 'Security',
                        'faq' => 'FAQ',
                        'contact' => 'Contact',
                        'footer' => 'Footer',
                        'sharia' => 'Sharia (ARAB)',
                        'technology' => 'Technology (CHINA)',
                        'testimonials' => 'Testimonials',
                    ])
                    ->required()
                    ->searchable()
                    ->native(false),
                Forms\Components\Select::make('locale')
                    ->options([
                        'ar' => 'Arabic (ar)',
                        'zh_CN' => 'Chinese (zh_CN)',
                        'en' => 'English (en)',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('status')
                    ->options(collect(ContentStatus::cases())->mapWithKeys(
                        fn (ContentStatus $status) => [$status->value => $status->label()]
                    ))
                    ->required()
                    ->native(false),
                Forms\Components\KeyValue::make('payload')
                    ->keyLabel('Field')
                    ->valueLabel('Value')
                    ->reorderable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section_key')
                    ->label('Section')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('locale')
                    ->sortable(),
                Tables\Columns\TextColumn::make('scope')
                    ->label('Scope')
                    ->badge()
                    ->state(fn (ContentSection $record) => match (true) {
                        $record->isSiteOverride() => $record->site?->name ?? 'Site',
                        $record->isGroupScoped() => $record->siteGroup?->name ?? 'Group',
                        default => 'Global',
                    })
                    ->color(fn (ContentSection $record) => match (true) {
                        $record->isSiteOverride() => 'warning',
                        $record->isGroupScoped() => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ContentStatus|string $state) => $state instanceof ContentStatus ? $state->label() : $state)
                    ->color(fn (ContentStatus|string $state) => ($state instanceof ContentStatus ? $state : ContentStatus::tryFrom((string) $state)) === ContentStatus::Published ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('section_key')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(ContentStatus::cases())->mapWithKeys(
                        fn (ContentStatus $status) => [$status->value => $status->label()]
                    )),
                Tables\Filters\SelectFilter::make('locale')
                    ->options([
                        'ar' => 'Arabic',
                        'zh_CN' => 'Chinese',
                        'en' => 'English',
                    ]),
                Tables\Filters\SelectFilter::make('section_key')
                    ->label('Section')
                    ->options([
                        'hero' => 'Hero',
                        'about' => 'About',
                        'tokenomics' => 'Tokenomics',
                        'roadmap' => 'Roadmap',
                        'team' => 'Team',
                        'partners' => 'Partners',
                        'security' => 'Security',
                        'faq' => 'FAQ',
                        'contact' => 'Contact',
                        'footer' => 'Footer',
                        'sharia' => 'Sharia (ARAB)',
                        'technology' => 'Technology (CHINA)',
                        'testimonials' => 'Testimonials',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->canWrite()),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return AdminScope::fromSession()->applyToContentSections(parent::getEloquentQuery());
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentSections::route('/'),
            'create' => Pages\CreateContentSection::route('/create'),
            'edit' => Pages\EditContentSection::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', ContentSection::class) ?? false;
    }
}
