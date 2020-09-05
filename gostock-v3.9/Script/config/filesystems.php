<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'default' => [
             'driver' => 'local',
             'root' => public_path(),
             'url' => env('APP_URL').'/public',
             'visibility' => 'public',
        ],

        'dospace' => [
          'driver' => 's3',
          'key' => env('DOS_ACCESS_KEY_ID'),
          'secret' => env('DOS_SECRET_ACCESS_KEY'),
          'region' => env('DOS_DEFAULT_REGION'),
          'bucket' => env('DOS_BUCKET'),
          'endpoint' => 'https://'.env('DOS_DEFAULT_REGION').'.digitaloceanspaces.com',
        ],

        'wasabi' => [
          'driver' => 's3',
          'key' => env('WAS_ACCESS_KEY_ID'),
          'secret' => env('WAS_SECRET_ACCESS_KEY'),
          'region' => env('WAS_DEFAULT_REGION'),
          'bucket' => env('WAS_BUCKET'),
          'endpoint' => 'https://s3.'.env('WAS_DEFAULT_REGION').'.wasabisys.com'
        ],

    ],

];
