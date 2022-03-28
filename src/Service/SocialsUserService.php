<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class SocialsUserService
{
    private function getUserBase() : ?User{
        return (new User())
            ->setRegisterDate(new \DateTime())
            ->setLikes(0)
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