<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\CloudService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/{_locale<%app.supported_locales%>}/profile/{id}', name: 'profile')]
    public function index(int $id, EntityManagerInterface $entityManager, CloudService $cloud): Response
    {
        $users = $entityManager->getRepository(User::class);
        $user = $users->find($id);
        if($user){
            return $this->render('profile/index.html.twig', [
                'user' => $user,
                'total_likes' => $users->getTotalLikes($user->getId()),
            ]);
        }
        return $this->redirectToRoute('main');
    }
}
