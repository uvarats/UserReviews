<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Service\LikeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikesController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private LikeService $likeService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param LikeService $likeService
     */
    public function __construct(EntityManagerInterface $entityManager, LikeService $likeService)
    {
        $this->entityManager = $entityManager;
        $this->likeService = $likeService;
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
    #[Route('/review/{state}/{id}', name: 'review_like', requirements: ['state' => '\b(?:dis)?like\b'])]
    public function like(string $state, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user */
        $user = $this->getUser();
        $review = $this->entityManager->getRepository(Review::class)->findOneBy(['id' => $id]);
        if($review){
            $this->likeService->setState($review->getId(), $user->getId(), $state);
            return new JsonResponse([
                'review_id' => $review->getId(),
                'user_id' => $user->getId(),
                'state' => $state,
            ]);
        } else {
            return new JsonResponse([
                'error' => 'Unknown review :(',
            ]);
        }
    }
}
