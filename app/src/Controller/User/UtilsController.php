<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\Type\AvatarType;
use App\Form\Type\PasswordChangeType;
use App\Service\AvatarServiceInterface;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UtilsController extends AbstractController
{

    private UserService $userService;

    private Security $security;

    private TranslatorInterface $translator;

    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructor.
     *
     * @param UserService $userService
     * @param Security $security
     * @param TranslatorInterface $translator Translator
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserService $userService, Security $security, TranslatorInterface $translator, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userService = $userService;
        $this->security = $security;
        $this->translator = $translator;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route(path: '/change_password', name: 'user_change_password')]
    public function changePassword(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $form = $this->createForm(
            PasswordChangeType::class,
            $user,
            ['action' => $this->generateUrl('user_change_password')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('password')->getData();
            $newPassword = $form->get('new_password')->getData();
            if (!strcmp($user->getPassword(), $oldPassword)) { // $this->passwordHasher->isPasswordValid doesn't work for some reason
                $user->setPassword(
                    $this->passwordHasher->hashPassword($user, $newPassword)
                );
                $this->userService->saveUser($user);
                $this->addFlash(
                    'success',
                    $this->translator->trans('message.created_successfully')
                );
            }
            else {
                $this->addFlash(
                    'warning',
                    $this->translator->trans('message.invalid_password')
                );
                    return $this->redirectToRoute('index');
            }
        }

            return $this->render('user/change_password.html.twig',
                [
                'form' => $form->createView(),
                'user' => $user
                ]
            );
    }
}
