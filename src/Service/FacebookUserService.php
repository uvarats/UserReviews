<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FacebookUserService
{
    private EntityManagerInterface $entityManager;
    private SocialsUserService $socialsUserService;
    private CloudService $cloudService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SocialsUserService $socialsUserService
     * @param CloudService $cloudService
     */
    public function __construct(EntityManagerInterface $entityManager, SocialsUserService $socialsUserService, CloudService $cloudService)
    {
        $this->entityManager = $entityManager;
        $this->socialsUserService = $socialsUserService;
        $this->cloudService = $cloudService;
    }


    public function getPassport(OAuth2ClientInterface $client, AccessToken $accessToken) : SelfValidatingPassport{
        return new SelfValidatingPassport(new UserBadge($accessToken->getToken(), function() use($client, $accessToken){
            $userRepository = $this->entityManager->getRepository(User::class);
            /** @var FacebookUser $facebookUser */
            $facebookUser = $client->fetchUserFromToken($accessToken);
            $existingUser = $userRepository
                ->findOneBy(['facebookId' => $facebookUser->getId()]);
            if($existingUser){
                return $existingUser;
            }
            $user = $userRepository
                ->findOneBy(['email' => $facebookUser->getEmail()]);
            if($user){
                $user->setFacebookId($facebookUser->getId());
            } else {
                $user = $this->socialsUserService->getFacebookUser($facebookUser);
            }
            $user->setAvatarUrl($this->cloudService->getDefaultAvatar());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $user;
        }));
    }
}