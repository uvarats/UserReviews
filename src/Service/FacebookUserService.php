<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FacebookUserService
{
    private EntityManagerInterface $entityManager;
    private SocialsUserService $socialsUserService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SocialsUserService $socialsUserService
     */
    public function __construct(EntityManagerInterface $entityManager, SocialsUserService $socialsUserService)
    {
        $this->entityManager = $entityManager;
        $this->socialsUserService = $socialsUserService;
    }

    public function getPassport(OAuth2ClientInterface $client, AccessToken $accessToken) : SelfValidatingPassport{
        return new SelfValidatingPassport(new UserBadge($accessToken->getToken(), function() use($client, $accessToken){
            /**
             * @var FacebookUser $facebookUser
             */
            $facebookUser = $client->fetchUserFromToken($accessToken);
            $existingUser = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy(['facebookId' => $facebookUser->getId()]);
            if($existingUser){
                return $existingUser;
            }
            $user = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy(['email' => $facebookUser->getEmail()]);
            if($user){
                $user->setFacebookId($facebookUser->getId());
            } else {
                $user = $this->socialsUserService->getFacebookUser($facebookUser);
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $user;
        }));
    }
}