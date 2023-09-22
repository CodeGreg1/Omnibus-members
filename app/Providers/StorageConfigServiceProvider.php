<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StorageConfigServiceProvider extends ServiceProvider
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
        $this->setStorageDriver();

        $this->setAws();
    }

    protected function setStorageDriver()
    {
        config([
            'filesystems.default' =>
            setting(
                'storage_driver',
                env('FILESYSTEM_DISK')
            )
        ]);
    }

    protected function setAws()
    {
        config([
            'filesystems.disks.s3.key' =>
            setting(
                'aws_access_key_id',
                env('AWS_ACCESS_KEY_ID')
            )
        ]);

        config([
            'filesystems.disks.s3.secret' =>
            setting(
                'aws_secret_access_key',
                env('AWS_SECRET_ACCESS_KEY')
            )
        ]);

        config([
            'filesystems.disks.s3.region' =>
            setting(
                'aws_default_region',
                env('AWS_DEFAULT_REGION')
            )
        ]);

        config([
            'filesystems.disks.s3.bucket' =>
            setting(
                'aws_bucket',
                env('AWS_BUCKET')
            )
        ]);

        config([
            'filesystems.disks.s3.endpoint' =>
            setting(
                'aws_endpoint',
                env('AWS_ENDPOINT')
            )
        ]);
    }
}