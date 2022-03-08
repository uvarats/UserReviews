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
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/review/add', name: 'review_add')]
    public function addReview(Request $request, LoggerInterface $logger){
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
            $this->entityManager->persist($review);
            $this->entityManager->flush();
            return $this->redirectToRoute('main');
        }
        return $this->renderForm('review/add.html.twig', [
                'form' => $form,
            ]
        );
    }
    #[Route('/review/delete/{id}', name: 'review_delete')]
    public function delete(int $id){

    }
    #[Route('/review/{id}', name: 'review')]
    public function index(int $id): Response
    {
        $review = $this->entityManager
            ->getRepository(Review::class)
            ->find($id);
        if($review){
            return $this->render('review/index.html.twig', [
                'review' => $review,
            ]);
        }
        return $this->redirectToRoute('main');
    }
}
