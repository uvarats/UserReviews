<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Service\CloudService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/get/username/{id}', name: 'get_username')]
    public function getAuthor(int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        return $this->render('get/_author.html.twig', [
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
        return $this->render('get/_sentence.html.twig', [
            'sentence' => $sentence,
        ]);
    }
    #[Route('get/avatar/{id}', name: 'get_avatar')]
    public function getAvatar(int $id, CloudService $cloud){
        return $this->render('get/_avatar.html.twig', [
            'avatar_url' => $cloud->getUserAvatar($id),
        ]);
    }
}
