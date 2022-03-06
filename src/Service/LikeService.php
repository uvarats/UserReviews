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
    public function setState(int $reviewId, int $userId, string $state){
        $review = $this->entityManager
            ->getRepository(Review::class)
            ->findOneBy(['id' => $reviewId]);
        $likes = $review->getLikes();
        $dislikes = $review->getDislikes();
        if($state === 'like'){
            $this->setId($userId, $likes, $dislikes);
        } else if($state === 'dislike'){
            $this->setId($userId, $dislikes, $likes);
        }
        $review->setLikes($likes);
        $review->setDislikes($dislikes);
        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }
    private function setId($userId, $likes, $dislikes){
        if(!in_array($userId, $likes, true)){
            if(in_array($userId, $dislikes, true)){
                $this->removeUserId($dislikes, $userId);
            }
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