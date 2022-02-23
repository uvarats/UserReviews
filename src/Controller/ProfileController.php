<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'profile')]
    public function index(int $id, ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();
//        if($currentUser->getUserIdentifier() === 'admin'){
//            $currentUser->setRoles(['ROLE_ADMIN']);
//            $entityManager->flush();
//        }
        $user = $entityManager->getRepository(User::class)->find($id);
        if($user){
            return $this->render('profile/index.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->redirectToRoute('main');
    }
}