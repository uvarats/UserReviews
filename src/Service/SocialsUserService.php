<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;

class SocialsUserService
{
    private function getUserBase() : ?User{
        return (new User())
            ->setRegisterDate(new \DateTime())
            ->setLikes(0)
            ->setTheme(0)
            ->setLocale('en')
            ->setPassword('0');
    }
    public function getGoogleUser(GoogleUser $googleUser) : ?User{
        return $this->getUserBase()
            ->setUsername($googleUser->getName())
            ->setEmail($googleUser->getEmail())
            ->setGoogleId($googleUser->getId())
            ->setAvatarUrl($googleUser->getAvatar());
    }
    public function getFacebookUser(FacebookUser $facebookUser) : ?User {
        return $this->getUserBase()
            ->setUsername($facebookUser->getName())
            ->setEmail($facebookUser->getEmail())
            ->setFacebookId($facebookUser->getId())
            ->setAvatarUrl('https://images.squarespace-cdn.com/content/v1/54b7b93ce4b0a3e130d5d232/1519987020970-8IQ7F6Z61LLBCX85A65S/icon.png');
    }
}