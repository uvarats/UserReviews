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
        $dislikes = $review->getDislikes();
        if(!in_array($userId, $likes, true)){
            if(in_array($userId, $dislikes, true)){
                $key = array_search($userId, $dislikes, true);
                unset($dislikes[$key]);
            }
            $likes[] = $userId;
        } else {
            $key = array_search($userId, $likes, true);
            unset($likes[$key]);
        }
        $review->setLikes($likes);
        $review->setDislikes($dislikes);
        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }
    public function dislike(int $reviewId, int $userId){
        $review = $this->entityManager
            ->getRepository(Review::class)
            ->findOneBy(['id' => $reviewId]);
        $likes = $review->getLikes();
        $dislikes = $review->getDislikes();
        if(!in_array($userId, $dislikes, true)){
            if(in_array($userId, $likes, true)){
                $this->removeUserId($likes, $userId);
            }
            $dislikes[] = $userId;
        } else {
            $this->removeUserId($dislikes, $userId);
        }
        $review->setLikes($likes);
        $review->setDislikes($dislikes);
        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }
    private function removeUserId(array &$ids, int $userId){
        $key = array_search($userId, $ids);
        unset($ids[$key]);
    }
}