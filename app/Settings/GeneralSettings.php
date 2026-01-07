<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public ?string $site_logo;
    public ?string $site_favicon;
    public ?string $support_email;
    public ?string $info_email;
    public ?string $address;
    public ?string $phone;
    public ?string $footer_copyright;
    public ?string $facebook_url;
    public ?string $whatsapp_url;
    public ?string $instagram_url;


    public static function group(): string
    {
        return 'general';
    }
}
