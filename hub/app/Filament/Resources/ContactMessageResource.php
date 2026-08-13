<?php

namespace App\Filament\Resources;

use App\Enums\LeadStatus;
use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use App\Services\AdminScope;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Leads';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Lead';

    protected static ?string $pluralModelLabel = 'Leads';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options(collect(LeadStatus::cases())->mapWithKeys(
                        fn (LeadStatus $status) => [$status->value => $status->label()]
                    ))
                    ->required()
                    ->native(false)
                    ->disabled(fn () => ! auth()->user()?->canWrite()),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Lead details')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('site.name')->label('Site'),
                        Infolists\Components\TextEntry::make('locale'),
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('phone'),
                        Infolists\Components\TextEntry::make('messenger'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (LeadStatus|string $state) => $state instanceof LeadStatus ? $state->label() : $state),
                        Infolists\Components\TextEntry::make('created_at')->dateTime(),
                        Infolists\Components\TextEntry::make('message')
                            ->columnSpanFull(),
                        Infolists\Components\KeyValueEntry::make('meta')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('site.name')
                    ->label('Site')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('locale')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (LeadStatus|string $state) => $state instanceof LeadStatus ? $state->label() : $state)
                    ->color(fn (LeadStatus|string $state) => match ($state instanceof LeadStatus ? $state->value : (string) $state) {
                        LeadStatus::New->value => 'warning',
                        LeadStatus::Qualified->value => 'success',
                        LeadStatus::Spam->value => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(LeadStatus::cases())->mapWithKeys(
                        fn (LeadStatus $status) => [$status->value => $status->label()]
                    )),
                Tables\Filters\SelectFilter::make('site_id')
                    ->label('Site')
                    ->relationship('site', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()?->canWrite()),
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return AdminScope::fromSession()->applyToContactMessages(parent::getEloquentQuery());
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
