<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Service\ReviewsService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/')]
    #[Route('/main', name: 'main')]
    public function index(ReviewsService $reviewsService): Response
    {
        return $this->render('main/index.html.twig', [
            'lastReviews' => $reviewsService->getNewReviews(),
            'topReviews' => $reviewsService->getTopReviews(),
        ]);
    }
}
