<?php

namespace App\Security;

use App\Entity\User;
use App\Service\SocialsUserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FacebookAuthenticator extends OAuth2Authenticator
{
    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;
    private SocialsUserService $socialsUserService;

    /**
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $entityManager
     * @param RouterInterface $router
     * @param SocialsUserService $socialsUserService
     */
    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router, SocialsUserService $socialsUserService)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->socialsUserService = $socialsUserService;
    }


    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function authenticate(Request $request)
    {
        $client = $this->clientRegistry->getClient('facebook_main');
        $accessToken = $this->fetchAccessToken($client);
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

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetUrl = $this->router->generate('main');
        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}