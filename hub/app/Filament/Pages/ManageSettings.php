<?php

namespace App\Filament\Pages;

use App\Models\HubSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Global settings';

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->canWrite() ?? false;
    }

    public function mount(): void
    {
        $fxRates = HubSetting::get('fx_rates', [
            'USD' => 1,
            'AED' => 3.67,
            'SAR' => 3.75,
            'QAR' => 3.64,
            'CNY' => 7.25,
        ]);

        $featureFlags = HubSetting::get('feature_flags', [
            'calculator_enabled' => true,
            'whitepaper_download' => true,
            'contact_form' => true,
        ]);

        $this->form->fill([
            'presale_price_usd' => HubSetting::get('presale_price_usd', 0.05),
            'fx_rates' => collect($fxRates)->map(fn ($rate, $currency) => [
                'currency' => $currency,
                'rate' => $rate,
            ])->values()->all(),
            'feature_flags' => $featureFlags,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pre-sale')
                    ->schema([
                        Forms\Components\TextInput::make('presale_price_usd')
                            ->label('Pre-sale price (USD)')
                            ->numeric()
                            ->required()
                            ->step(0.0001)
                            ->minValue(0),
                    ]),
                Forms\Components\Section::make('FX rates (vs USD)')
                    ->schema([
                        Forms\Components\Repeater::make('fx_rates')
                            ->schema([
                                Forms\Components\TextInput::make('currency')
                                    ->required()
                                    ->maxLength(8),
                                Forms\Components\TextInput::make('rate')
                                    ->numeric()
                                    ->required()
                                    ->step(0.0001)
                                    ->minValue(0),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add currency'),
                    ]),
                Forms\Components\Section::make('Feature flags')
                    ->schema([
                        Forms\Components\Toggle::make('feature_flags.calculator_enabled')
                            ->label('Investment calculator'),
                        Forms\Components\Toggle::make('feature_flags.whitepaper_download')
                            ->label('Whitepaper download'),
                        Forms\Components\Toggle::make('feature_flags.contact_form')
                            ->label('Contact form'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        HubSetting::set('presale_price_usd', ['value' => (float) $data['presale_price_usd']]);

        $fxRates = collect($data['fx_rates'] ?? [])
            ->filter(fn (array $row) => ! empty($row['currency']))
            ->mapWithKeys(fn (array $row) => [$row['currency'] => (float) $row['rate']])
            ->all();

        HubSetting::set('fx_rates', $fxRates);
        HubSetting::set('feature_flags', $data['feature_flags'] ?? []);

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
