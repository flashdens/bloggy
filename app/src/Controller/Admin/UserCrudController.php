<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Type\PasswordResetType;
use App\Service\UserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserCrudController.
 */
#[Route('/admin/user')]
#[IsGranted('ROLE_ADMIN')]
class UserCrudController extends AbstractController
{
    /**
     * User service.
     */
    private UserServiceInterface $userService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * UserCrudController constructor.
     *
     * @param UserServiceInterface $userService The user service
     * @param TranslatorInterface  $translator  The translator
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Show all users.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'admin_user', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $pagination = $this->userService->getPaginatedList($request->query->getInt('page', 1));

        return $this->render('admin/user/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * View user.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/view/{id}',
        name: 'admin_view_user',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function view(Request $request, User $user): Response
    {
        $comments = $this->userService->getPaginatedListOfComments($request->query->getInt('page', 1), $user);

        return $this->render(
            'admin/user/view.html.twig',
            [
                'user' => $user,
                'comments' => $comments,
            ]
        );
    }

    /**
     * Edit user.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/edit/{id}',
        name: 'admin_edit_user',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(
            PasswordResetType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_user', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->saveUser($user);
            $this->addFlash(
                'success',
                $this->translator->trans('post.edited_successfully')
            );

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/user/edit.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }

    /**
     * Delete user.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/delete/{id}',
        name: 'admin_delete_user',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function delete(Request $request, User $user): Response
    {
        $form = $this->createForm(
            FormType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_delete_user', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->deleteUser($user);
            $this->addFlash(
                'success',
                $this->translator->trans('user.deleted_successfully')
            );

            return $this->redirectToRoute('admin_user');
        }

        return $this->render(
            'admin/user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Ban user.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/ban/{id}',
        name: 'admin_ban_user',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function ban(Request $request, User $user): Response
    {
        $form = $this->createForm(
            FormType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_ban_user', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->banOrUnbanUser($user);
            $this->addFlash(
                'success',
                $this->translator->trans('user.banned_successfully')
            );

            return $this->redirectToRoute('admin_user');
        }

        return $this->render(
            'admin/user/ban.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
