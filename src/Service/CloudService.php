<?php

namespace App\Service;

use Cloudinary\Cloudinary;

class CloudService
{
    private Cloudinary $cloudinary;

    /**
     * @param Cloudinary $cloudinary
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

    /**
     * @return Cloudinary
     */
    public function getCloudinary(): Cloudinary
    {
        return $this->cloudinary;
    }
}