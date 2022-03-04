<?php

namespace App\Service;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;

class ReviewsService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getNewReviews(): array
    {
        $reviews = $this->getAllReviews();
        $lastReviews = array_slice($reviews, -5);
        return array_reverse($lastReviews);

    }
    public function getTopReviews(): array
    {
        $reviews = $this->getAllReviews();
        usort($reviews, [$this, 'compareByLikes']);
        return array_slice($reviews, 0, 5);
    }
    #[Pure]
    private function compareByLikes(Review $reviewA, Review $reviewB): int
    {
        $likesA = $reviewA->getLikes();
        $likesB = $reviewB->getLikes();
        if(count($likesA) === count($likesB)){
            return 0;
        }
        return count($likesA) < count($likesB) ? -1 : 1;
    }
    private function getAllReviews(): array
    {
        return $this->entityManager->getRepository(Review::class)->findAll();
    }
}