<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use DateTime;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;

class SocialsUserService
{
    private function getUserBase() : ?User{
        return (new User())
            ->setRegisterDate(new DateTime())
            ->setPassword('0');
    }

    public function getGoogleUser(GoogleUser $googleUser) : ?User{
        return $this->getUserBase()
            ->setUsername($googleUser->getName())
            ->setEmail($googleUser->getEmail())
            ->setGoogleId($googleUser->getId());
    }
    public function getFacebookUser(FacebookUser $facebookUser) : ?User {
        return $this->getUserBase()
            ->setUsername($facebookUser->getName())
            ->setEmail($facebookUser->getEmail())
            ->setFacebookId($facebookUser->getId());
    }
}