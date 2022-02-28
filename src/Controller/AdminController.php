<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }
}
