<?php

namespace App\Service;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

class ReviewsService
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
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
        $likesA = count($reviewA->getLikes());
        $likesB = count($reviewB->getLikes());
        if($likesA === $likesB){
            return 0;
        }
        return $likesA > $likesB ? -1 : 1;
    }
    private function getAllReviews(): array
    {
        return $this->entityManager->getRepository(Review::class)->findAll();
    }
}