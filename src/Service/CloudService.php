<?php

declare(strict_types=1);

namespace App\Service;

use CarlosOCarvalho\Flysystem\Cloudinary\CloudinaryAdapter;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Cloudinary;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\UriInterface;

class CloudService
{
    private Filesystem $filesystem;

    /**
     * @param string $cloudName
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct(string $cloudName, string $apiKey, string $apiSecret)
    {
        $adapter = new CloudinaryAdapter([
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'cloud_name' => $cloudName
        ]);
        $this->filesystem = new Filesystem($adapter);
    }


    public function getDefaultAvatar(): UriInterface|string
    {
        try {
            $this->filesystem->createDirectory("test_dir");
        } catch (FilesystemException $e) {
        }
        return "";
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }
}
