<?php

declare(strict_types=1);

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    private ClientRegistry $clientRegistry;

    /**
     * @param ClientRegistry $clientRegistry
     */
    public function __construct(ClientRegistry $clientRegistry)
    {
        $this->clientRegistry = $clientRegistry;
    }


    #[Route('/connect/google', name: 'connect_google_start')]
    public function index(): Response
    {
        return $this
            ->clientRegistry
            ->getClient('google_main')
            ->redirect([]);
    }
    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheck(Request $request){
    }
}
