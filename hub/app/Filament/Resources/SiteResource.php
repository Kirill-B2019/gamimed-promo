<?php

namespace App\Filament\Resources;

use App\Enums\SiteStatus;
use App\Filament\Resources\SiteResource\Pages;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Sites';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state, ?string $operation) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug((string) $state));
                        }
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(64)
                    ->unique(ignoreRecord: true)
                    ->alphaDash(),
                Forms\Components\TextInput::make('domain')
                    ->maxLength(255)
                    ->placeholder('arab.example.com'),
                Forms\Components\Select::make('default_locale')
                    ->options([
                        'ar' => 'Arabic (ar)',
                        'zh_CN' => 'Chinese (zh_CN)',
                        'en' => 'English (en)',
                    ])
                    ->required(),
                Forms\Components\TagsInput::make('locales')
                    ->placeholder('Add locale')
                    ->suggestions(['ar', 'zh_CN', 'en'])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(collect(SiteStatus::cases())->mapWithKeys(
                        fn (SiteStatus $status) => [$status->value => $status->label()]
                    ))
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('groups')
                    ->relationship('groups', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Forms\Components\KeyValue::make('settings')
                    ->keyLabel('Key')
                    ->valueLabel('Value')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('domain')->toggleable(),
                Tables\Columns\TextColumn::make('default_locale')->label('Locale'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (SiteStatus|string $state) => $state instanceof SiteStatus ? $state->label() : $state)
                    ->color(fn (SiteStatus|string $state) => ($state instanceof SiteStatus ? $state : SiteStatus::tryFrom((string) $state))?->value === 'active' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('groups.name')->badge()->separator(','),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(SiteStatus::cases())->mapWithKeys(
                        fn (SiteStatus $status) => [$status->value => $status->label()]
                    )),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('issueToken')
                    ->label('Issue API token')
                    ->icon('heroicon-o-key')
                    ->visible(fn (Site $record) => auth()->user()?->can('manageToken', $record))
                    ->requiresConfirmation()
                    ->modalHeading('Issue new site API token')
                    ->modalDescription('This creates a new Sanctum token for the site. Store it in the front app env; it will only be shown once.')
                    ->action(function (Site $record) {
                        $record->tokens()->delete();
                        $token = $record->createToken('site-api', ['site:api'])->plainTextToken;

                        Notification::make()
                            ->title('API token created')
                            ->body($token)
                            ->success()
                            ->persistent()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->isSuperAdmin()),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && ! $user->isSuperAdmin()) {
            $query->whereIn('id', $user->accessibleSiteIds());
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create', Site::class) ?? false;
    }
}
