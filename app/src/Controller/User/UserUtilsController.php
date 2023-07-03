<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\Type\PasswordChangeType;
use App\Form\Type\UserType;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserUtilsController.
 */
#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UserUtilsController extends AbstractController
{
    /**
     * UserService.
     */
    private UserService $userService;

    /**
     * Security.
     */
    private Security $security;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * UserPasswordHasherInterface.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * UserUtilsController constructor.
     *
     * @param UserService                 $userService    User service
     * @param Security                    $security       Security
     * @param TranslatorInterface         $translator     Translator
     * @param UserPasswordHasherInterface $passwordHasher Password Hasher
     */
    public function __construct(UserService $userService, Security $security, TranslatorInterface $translator, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userService = $userService;
        $this->security = $security;
        $this->translator = $translator;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Change password action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(path: '/change_password', name: 'user_edit_password')]
    public function changePassword(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $form = $this->createForm(
            PasswordChangeType::class,
            $user,
            ['action' => $this->generateUrl('user_edit_password')]
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
            } else {
                $this->addFlash(
                    'warning',
                    $this->translator->trans('message.invalid_password')
                );

                return $this->redirectToRoute('index');
            }
        }

        return $this->render(
            'user/utils/change_password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Change email action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(path: '/change_email', name: 'user_edit_email')]
    public function changeUsername(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $form = $this->createForm(
            UserType::class,
            $user,
            ['action' => $this->generateUrl('user_edit_email')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmail($form->get('email')->getData());
            $this->userService->saveUser($user);
            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'user/utils/change_email.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
