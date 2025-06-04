<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => env('APP_ENV') !== 'production' ? 'local' : 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'throw' => true,
        ],
        'post-images' => [
            'driver' => env('APP_ENV') !== 'production' ? 'local' : 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('APP_ENV') !== 'production' ? 'post-images' : env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
            'root' => env('APP_ENV') !== 'production' ? storage_path('app/post-images') : 'post-images',
        ],
        'post-audio' => [
            'driver' => env('APP_ENV') !== 'production' ? 'local' : 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('APP_ENV') !== 'production' ? 'post-audio' : env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
            'root' => env('APP_ENV') !== 'production' ? storage_path('app/post-audio') : 'post-audio',
        ],
        'profile-pictures' => [
            'driver' => env('APP_ENV') !== 'production' ? 'local' : 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('APP_ENV') !== 'production' ? 'profile-pictures' : env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
            'root' => env('APP_ENV') !== 'production' ? storage_path('app/profile-pictures') : 'profile-pictures',
        ],
        'post-comment-audio' => [
            'driver' => env('APP_ENV') !== 'production' ? 'local' : 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('APP_ENV') !== 'production' ? 'post-comment-audio' : env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
            'root' => env('APP_ENV') !== 'production' ? storage_path('app/post-comment-audio') : 'post-comment-audio',
        ],
        'profile-comment-audio' => [
            'driver' => env('APP_ENV') !== 'production' ? 'local' : 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('APP_ENV') !== 'production' ? 'profile-comment-audio' : env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
            'root' => env('APP_ENV') !== 'production' ? storage_path('app/profile-comment-audio') : 'profile-comment-audio',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('post-images') => storage_path('app/post-images'),
        public_path('post-audio') => storage_path('app/post-audio'),
        public_path('profile-pictures') => storage_path('app/profile-pictures'),
        public_path('post-comment-audio') => storage_path('app/post-comment-audio'),
        public_path('profile-comment-audio') => storage_path('app/profile-comment-audio'),
    ],

];
