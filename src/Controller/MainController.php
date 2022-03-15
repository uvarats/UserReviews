<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Service\CloudService;
use App\Service\ReviewsService;
use ContainerIjylEKr\getFosElastica_Index_ReviewService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Finder\FinderInterface;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
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
    public function index(): Response
    {
        $repository = $this->entityManager->getRepository(Review::class);
        return $this->render('main/index.html.twig', [
            'lastReviews' => $repository->getNewReviews(5),
            'topReviews' => $repository->getTopReviews(5),
        ]);
    }
}
