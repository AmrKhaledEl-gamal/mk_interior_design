<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Project extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $fillable = ['name', 'active', 'user_id', 'video_urls', 'slug'];

    public $translatable = ['name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'active' => 'boolean',
        'video_urls' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        // $this
        //     ->addMediaCollection('videos')
        //     ->acceptsMimeTypes(['video/mp4', 'video/quicktime', 'video/avi', 'video/mpeg']);
    }
}
