<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewAddingType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    #[Route('/review/add', name: 'review_add')]
    public function addReview(Request $request, ManagerRegistry $doctrine){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $review = new Review();
        $form = $this->createForm(ReviewAddingType::class, $review);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $task = $form->getData();

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
