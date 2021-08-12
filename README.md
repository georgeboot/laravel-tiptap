# Tiptap Editor for TALL stack

<p align="center"><img src="/screenshot.png" alt="Screenshot"></p>

```
composer require georgeboot/laravel-tiptap
yarn add laravel-tiptap
```

In your `app.js`:

```js
import Alpine from 'alpinejs'
import LaravelTiptap from 'laravel-tiptap' // add this
Alpine.data('tiptapEditor', LaravelTiptap) // add this
Alpine.start()
```

In your blade file:
```blade
<x-tiptap-editor />

<!-- enable image upload -->
<x-tiptap-editor enable-image-upload />
```

## Image upload
Ensure you have your s3 disk configured correctly in s3:
```php
// config/filesystems.php

<?php

return [

    // other settings
    
    'disks' => [

        // other disks 

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'token' => env('AWS_SESSION_TOKEN'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            // 'url' => 'https://my-cloudfront-id.cloudfront.net', // optional: if you use cloudfront or some other cdn in front of s3
            'endpoint' => env('AWS_ENDPOINT'),
        ],

        // ...

    ],

];
```
