<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;

class GenerateUserSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-user-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::whereNull('slug')->chunk(100, function ($users) {
            foreach ($users as $user) {

                $baseSlug = Str::slug($user->first_name . '-' . $user->last_name);
                $slug = $baseSlug;
                $count = 1;

                while (User::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }

                $user->update(['slug' => $slug]);
            }
        });

        $this->info('User slugs generated successfully.');
    }
}
