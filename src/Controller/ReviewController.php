<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Review;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\ReviewAddingType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/{_locale<%app.supported_locales%>}/review/add', name: 'review_add')]
    public function addReview(Request $request): RedirectResponse|Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $review = new Review();
        $form = $this->createForm(ReviewAddingType::class, $review);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $this->getUser();
            /** @var Review $review */
            $review = $form->getData();
            $review->setLikes([])
                ->setCreationTime(new DateTime())
                ->setAuthor($user);
            $this->entityManager->persist($review);
            $this->entityManager->flush();
            return $this->redirectToRoute('review', ['id' => $review->getId()]);
        }
        return $this->renderForm('review/add.html.twig', [
                'form' => $form,
            ]
        );
    }
    #[Route('/{_locale<%app.supported_locales%>}/review/edit/{id}', name: 'review_edit')]
    public function edit(Review $review, Request $request): RedirectResponse|Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user */
        $user = $this->getUser();
        if($review->getAuthor()->getId() === $user->getId() || $this->isGranted('ROLE_ADMIN')){
            $form = $this->createForm(ReviewAddingType::class, $review);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                //$this->entityManager->persist($review);
                $this->entityManager->flush();
                return $this->redirectToRoute('review', ['id' => $review->getId()]);
            }
            return $this->renderForm('review/add.html.twig', [
                'form' => $form,
            ]);
        }
        return $this->redirectToRoute('main');
    }
    #[Route('/review/remove/{id}', name: 'review_remove', methods: ['POST'])]
    public function remove(int $id): RedirectResponse
    {
        $review = $this->entityManager->getRepository(Review::class)->find($id);
        /** @var User $user */
        $user = $this->getUser();
        if($review->getAuthor()->getId() === $user->getId() || $this->isGranted('ROLE_ADMIN')){
            $this->entityManager->remove($review);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('main');
    }

    #[Route('/comment/{id}/remove', name: 'remove_comment')]
    public function removeComment(int $id): JsonResponse
    {
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);
        if($comment){
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
            return new JsonResponse([
                'response' => 'success',
            ]);
        }
        return new JsonResponse([
            'error' => 'Unknown comment!',
        ]);
    }
    #[Route('/{_locale<%app.supported_locales%>}/review/{id}', name: 'review')]
    public function index(Request $request, int $id): Response
    {
        $review = $this->entityManager
            ->getRepository(Review::class)
            ->find($id);
        if($review){
            $comment = new Comment();
            /** @var User $user */
            $user = $this->getUser();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                /** @var Comment $comment */
                $comment = $form->getData();
                $comment->setAuthor($user)
                    ->setReview($review)
                    ->setPostdate(new DateTime());
                $this->entityManager->persist($comment);
                $this->entityManager->flush();
                return $this->redirect($request->getUri()); //redirect for form reset
            }
            return $this->renderForm('review/index.html.twig', [
                'review' => $review,
                'comment' => $form,
            ]);
        }
        return $this->redirectToRoute('main');
    }
}
