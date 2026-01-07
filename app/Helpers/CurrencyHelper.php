<?php

if (!function_exists('formatCurrency')) {
    /**
     * Format a price according to currency settings
     *
     * @param float $price
     * @param string|null $currency
     * @return string
     */
    function formatCurrency($price, $currency = null)
    {
        $currencySettings = app(\App\Settings\CurrencySettings::class);
        return $currencySettings->formatPrice($price, $currency);
    }
}

if (!function_exists('convertCurrency')) {
    /**
     * Convert price from one currency to another
     *
     * @param float $price
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    function convertCurrency($price, $fromCurrency, $toCurrency)
    {
        $currencySettings = app(\App\Settings\CurrencySettings::class);
        return $currencySettings->convertPrice($price, $fromCurrency, $toCurrency);
    }
}

if (!function_exists('currencySymbol')) {
    /**
     * Get the current currency symbol
     *
     * @return string
     */
    function currencySymbol()
    {
        $currencySettings = app(\App\Settings\CurrencySettings::class);
        return $currencySettings->getCurrencySymbol();
    }
}

if (!function_exists('defaultCurrency')) {
    /**
     * Get the default currency code
     *
     * @return string
     */
    function defaultCurrency()
    {
        $currencySettings = app(\App\Settings\CurrencySettings::class);
        return $currencySettings->default_currency;
    }
}

if (!function_exists('enabledCurrencies')) {
    /**
     * Get list of enabled currencies
     *
     * @return array
     */
    function enabledCurrencies()
    {
        $currencySettings = app(\App\Settings\CurrencySettings::class);
        return $currencySettings->getEnabledCurrencies();
    }
}

if (!function_exists('isCurrencyEnabled')) {
    /**
     * Check if a currency is enabled
     *
     * @param string $currency
     * @return bool
     */
    function isCurrencyEnabled($currency)
    {
        $currencySettings = app(\App\Settings\CurrencySettings::class);
        return $currencySettings->isCurrencyEnabled($currency);
    }
}

if (!function_exists('supportedCurrencies')) {
    /**
     * Get list of all supported currencies
     *
     * @return array
     */
    function supportedCurrencies()
    {
        $currencySettings = app(\App\Settings\CurrencySettings::class);
        return $currencySettings->supported_currencies;
    }
}
