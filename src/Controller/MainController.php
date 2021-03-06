<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Tag;
use App\Service\CloudService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function indexNoLocale(): RedirectResponse
    {
        return $this->redirectToRoute("main", ['_locale' => 'en']);
    }
    #[Route('/{_locale<%app.supported_locales%>}', name: 'main')]
    public function index(CloudService $cloud): Response
    {
        $reviewRepository = $this->entityManager->getRepository(Review::class);
        $tagRepository = $this->entityManager->getRepository(Tag::class);
        return $this->render('main/index.html.twig', [
            'lastReviews' => $reviewRepository->getNewReviews(5),
            'topReviews' => $reviewRepository->getTopReviews(5),
            'tags' => $tagRepository->getTagsByReviewsCount(),
        ]);
    }
}
