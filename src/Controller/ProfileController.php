<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CloudService;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\Resize;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/{_locale<%app.supported_locales%>}/profile/{id}', name: 'profile')]
    public function index(int $id, ManagerRegistry $doctrine, CloudService $cloud): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        if($user){
            return $this->render('profile/index.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->redirectToRoute('main');
    }
}
