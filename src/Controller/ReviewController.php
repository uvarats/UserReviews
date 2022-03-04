<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewAddingType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    #[Route('/review/add', name: 'review_add')]
    public function addReview(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $review = new Review();
        $form = $this->createForm(ReviewAddingType::class, $review);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var User $user
             */
            $user = $this->getUser();
            $a = 10;
            /**
             * @var Review $review
             */
            $review = $form->getData();
            $review->setLikes([])
                ->setCreationTime(new \DateTime())
                ->setUserId($user->getId())
                ->setSubjectId(0);
            $entityManager->persist($review);
            $entityManager->flush();
        }
        return $this->renderForm('review/add.html.twig', [
                'form' => $form,
            ]
        );
    }
    #[Route('/review/{id}', name: 'review')]
    public function index(int $id): Response
    {
        return $this->render('review/index.html.twig', [
            'controller_name' => 'ReviewController',
        ]);
    }
}
