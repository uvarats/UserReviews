<?php

namespace App\Controller;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/{_locale<%app.supported_locales%>}/tag/{tag}', name: 'app_tag')]
    public function index(string $tag): Response
    {
        $reviews = $this->entityManager->getRepository(Review::class);
        return $this->render('tag/index.html.twig', [
            'tag' => $tag,
            'results' => $reviews->getReviewsByTag($tag),
        ]);
    }
}
