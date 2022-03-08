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

    #[Route('/review/like/{id}', name: 'review_like', methods: ['POST'])]
    public function like(int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user */
        $user = $this->getUser();
        $review = $this->entityManager->getRepository(Review::class)->findOneBy(['id' => $id]);
        if($review){
            $this->likeService->like($review->getId(), $user->getId());
            return new JsonResponse([
                'review_id' => $review->getId(),
                'user_id' => $user->getId(),
                'new_count' => count($review->getLikes()),
            ]);
        } else {
            return new JsonResponse([
                'error' => 'Unknown review :(',
            ]);
        }
    }
}
