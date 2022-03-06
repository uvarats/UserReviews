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
    #[Route('/get/username/{id}', name: 'get_username')]
    public function getAuthor(int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        return $this->render('main/_author.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/get/sentence/{id}', name: 'get_first_sentence')]
    public function getFirstSentence(int $id): Response
    {
        $review = $this->entityManager
            ->getRepository(Review::class)
            ->findOneBy(['id' => $id]);
        $text = $review->getText();
        $substr_index = strpos($text, '. ');
        if($substr_index){
            $sentence = substr($text, 0, $substr_index + 1);
        } else {
            $sentence = substr($text, 0, 30);
        }
        return $this->render('main/_sentence.html.twig', [
            'sentence' => $sentence,
        ]);
    }
}
