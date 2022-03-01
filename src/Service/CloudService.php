<?php

namespace App\Service;

use Cloudinary\Cloudinary;

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

    /**
     * @return Cloudinary
     */
    public function getCloudinary(): Cloudinary
    {
        return $this->cloudinary;
    }
    public function setDefaultAvatar(int $id){
        $default_avatar = $this->cloudinary->image('/default/avatar')->toUrl();
        $this->cloudinary->uploadApi()->upload($default_avatar, [
            'public_id' => 'users/' . $id . '/avatar',
        ]);
    }
}