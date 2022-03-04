<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikesController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/likes/reset', name: 'reset_likes')]
    public function reset(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $reviews = $this->entityManager->getRepository(Review::class)->findAll();
        foreach ($reviews as $review){
            $review->setLikes([])->setDislikes([]);
        }
        $this->entityManager->flush();
        return new Response();
    }
    #[Route('/review/like/{id}', name: 'review_like')]
    public function like(int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $review = $this->entityManager->getRepository(Review::class)->findOneBy(['id' => $id]);
        $likes = $review->getLikes();
        $likes[] = $user->getId();
        $review->setLikes($likes);
        $this->entityManager->persist($review);
        $this->entityManager->flush();
        return new JsonResponse([
            'review_id' => $review->getId(),
            'user_id' => $user->getId(),
        ]);
    }
    #[Route('/review/dislike/{id}', name: 'review_dislike')]
    public function dislike(){
        return new Response('');
    }
}
