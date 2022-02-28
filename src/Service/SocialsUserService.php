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
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function getUserBase() : ?User{
        return (new User())
            ->setRegisterDate(new \DateTime())
            ->setLikes(0)
            ->setTheme(0)
            ->setLocale('en')
            ->setPassword('0');
    }

    public function getGoogleUser(GoogleUser $googleUser) : ?User{
        $baseUser = $this->getUserBase();
        return $baseUser
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