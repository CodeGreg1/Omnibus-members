<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MediaLibraryConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        config([
            'media-library.queue_conversions_by_default' => setting('media_queue_conversions') == 1
        ]);

        $driver = setting(
            'storage_driver',
            env('FILESYSTEM_DISK')
        );

        config([
            'media-library.disk_name' => $driver === 'local' ? 'public' : $driver
        ]);
    }
}
