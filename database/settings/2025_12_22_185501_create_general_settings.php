<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Mk AGENCY');
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.site_favicon', null);
        $this->migrator->add('general.support_email', null);
        $this->migrator->add('general.info_email', null);
        // address and phone
        $this->migrator->add('general.address', null);
        $this->migrator->add('general.phone', null);
        // footer copyright text
        $this->migrator->add('general.footer_copyright', 'Amr Elgamal');
        // social media links
        $this->migrator->add('general.facebook_url', null);
        $this->migrator->add('general.whatsapp_url', null);
        $this->migrator->add('general.instagram_url', null);
    }
};
