<?php

namespace App\Service;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;

class LikeService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function like(int $reviewId, int $userId){
        $review = $this->entityManager
            ->getRepository(Review::class)
            ->findOneBy(['id' => $reviewId]);
        $likes = $review->getLikes();
        $this->setId($userId, $likes);
        $review->setLikes($likes);
        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }
    private function setId($userId, array& $likes){
        if(!in_array($userId, $likes, true)){
            $likes[] = $userId;
        } else {
            $this->removeUserId($likes, $userId);
        }
    }
    private function removeUserId(array &$ids, int $userId){
        $key = array_search($userId, $ids);
        unset($ids[$key]);
    }
}