<?php

namespace Georgeboot\LaravelTiptap;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateImageUploadConfigController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $uuid = Str::uuid()->toString();
        $disk = Storage::disk(config('laravel-tiptap.images.disk'));

        // Flysystem V2+ (Laravel 9+) doesn't allow direct access to stuff, so we need to invade instead.

        /** @var \League\Flysystem\FilesystemOperator|\League\Flysystem\FilesystemInterface $driver */
        $driver = $disk->getDriver();

        /** @var \Illuminate\Filesystem\FilesystemAdapter $adapter */
        $adapter = method_exists($driver, 'getAdapter')
            ? $driver->getAdapter()
            : invade($driver)->adapter;

        /** @var \Aws\S3\S3Client $client */
        $client = method_exists($adapter, 'getClient')
            ? $adapter->getClient()
            : invade($adapter)->client;

        $bucketName = method_exists($adapter, 'getBucket')
            ? $adapter->getBucket()
            : invade($adapter)->bucket;

        $bucketPrefix = method_exists($adapter, 'getPathPrefix')
            ? $adapter->getPathPrefix()
            : invade($adapter)->prefixer->prefixPath('');

        $keyPrefix = $bucketPrefix.$uuid;

        $formInputs = [
            'acl' => 'public-read',
            'success_action_status' => '201',
        ];

        // Construct an array of conditions for policy
        $options = [
            ['acl' => 'public-read'],
            ['bucket' => $bucketName],
            ['success_action_status' => '201'],
            ['starts-with', '$key', "{$keyPrefix}/"],
            ['starts-with', '$Content-Type', 'image/'],
        ];

        $expires = '+12 hours';

        $postObject = new \Aws\S3\PostObjectV4(
            $client,
            $bucketName,
            $formInputs,
            $options,
            $expires
        );

        return response()->json([
            'uploadUrl' => $postObject->getFormAttributes()['action'],
            'uploadUrlFormData' => $postObject->getFormInputs(),
            'uploadKeyPrefix' => $keyPrefix,
            'downloadUrlPrefix' => $disk->url("{$uuid}/"),
        ]);
    }
}
