<?php

namespace App\Service;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Cloudinary;
use Psr\Http\Message\UriInterface;

class CloudService
{
    private Cloudinary $cloudinary;

    /**
     * @param string $cloudName
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct(string $cloudName, string $apiKey, string $apiSecret)
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret],
            'url' => [
                'secure' => true]]);
    }

    public function getCloudinary(): Cloudinary
    {
        return $this->cloudinary;
    }

    public function getDefaultAvatar(): UriInterface|string
    {
        return $this
            ->cloudinary
            ->image('/default/avatar')
            ->toUrl();
    }
}