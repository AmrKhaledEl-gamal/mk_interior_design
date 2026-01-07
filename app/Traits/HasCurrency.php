<?php

namespace App\Traits;

use App\Settings\CurrencySettings;

trait HasCurrency
{
    /**
     * Get formatted price for this model
     */
    public function getFormattedPriceAttribute()
    {
        if (!isset($this->price)) {
            return null;
        }

        return app(CurrencySettings::class)->formatPrice($this->price);
    }

    /**
     * Get price in different currency
     */
    public function getPriceInCurrency($currency)
    {
        if (!isset($this->price)) {
            return null;
        }

        $currencySettings = app(CurrencySettings::class);
        $defaultCurrency = $currencySettings->default_currency;

        $convertedPrice = $currencySettings->convertPrice($this->price, $defaultCurrency, $currency);
        return $currencySettings->formatPrice($convertedPrice, $currency);
    }

    /**
     * Check if price exists and is valid
     */
    public function hasValidPrice()
    {
        return isset($this->price) && is_numeric($this->price) && $this->price > 0;
    }

    /**
     * Get price with discount applied
     */
    public function getDiscountedPrice($discountPercent)
    {
        if (!$this->hasValidPrice()) {
            return null;
        }

        $discountedPrice = $this->price * (1 - $discountPercent / 100);
        return app(CurrencySettings::class)->formatPrice($discountedPrice);
    }
}
