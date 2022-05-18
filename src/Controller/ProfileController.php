<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileEditType;
use App\Service\CloudService;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;
use Elasticsearch\Common\Exceptions\Forbidden403Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/{_locale<%app.supported_locales%>}/profile/{id}', name: 'profile', priority: 0)]
    public function index(User $user, CloudService $cloud): Response
    {
        $users = $this->em->getRepository(User::class);
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'total_likes' => $users->getTotalLikes($user->getId()),
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/profile/edit', name: 'profile_edit', priority: 5)]
    public function edit(Request $request, FileUploader $uploader): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if($user){
            $form = $this->createForm(ProfileEditType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $avatar = $form->get('avatar')->getData();
                $username = $form->get('username')->getData();
                if($avatar){
                    $fileUrl = $uploader->upload($avatar);
                    $user->setAvatarUrl($fileUrl);
                }
                if($username){
                    $user->setUsername($username);
                }
                $this->em->flush();
                return $this->redirectToRoute('profile', ['id' => $user->getId()]);
            }
            return $this->renderForm('profile/edit-profile.html.twig', [
                'form' => $form,
            ]);
        }
        throw new AccessDeniedHttpException();
    }
}
