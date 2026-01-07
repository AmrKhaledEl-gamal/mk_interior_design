<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Settings\CurrencySettings;

class SetupCurrencySettings extends Command
{
    protected $signature = 'currency:setup';
    protected $description = 'Setup currency settings with default values';

    public function handle()
    {
        try {
            $this->info('Setting up currency settings...');

            // Delete existing settings first
            $this->info('Removing existing currency settings...');
            \DB::table('settings')->where('group', 'currency')->delete();

            // Run the settings migration manually
            $migrationFile = database_path('settings/2025_12_17_205127_create_currency_settings.php');

            if (file_exists($migrationFile)) {
                $migration = require $migrationFile;
                $migration->up();
                $this->info('Currency settings migration executed successfully.');
            } else {
                $this->error('Migration file not found: ' . $migrationFile);
                return 1;
            }

            // Test that settings can be loaded
            try {
                $currencySettings = app(\App\Settings\CurrencySettings::class);
                $this->info('Default currency: ' . $currencySettings->default_currency);
                $this->info('Currency symbol: ' . $currencySettings->currency_symbol);
            } catch (\Exception $e) {
                $this->error('Failed to load settings: ' . $e->getMessage());
                return 1;
            }

            $this->info('Currency settings have been set up successfully!');

        } catch (\Exception $e) {
            $this->error('Error setting up currency settings: ' . $e->getMessage());
            return 1;
        }
    }
}
