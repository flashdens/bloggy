<?php
/**
 * Avatar controller.
 */

namespace App\Controller\User;

use App\Entity\Avatar;
use App\Entity\User;
use App\Form\Type\AvatarType;
use App\Service\AvatarServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class AvatarController.
 */
#[Route('/user/avatar')]
class AvatarController extends AbstractController
{
    /**
     * Avatar service.
     */
    private AvatarServiceInterface $avatarService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    private Security $security;
    /**
     * Constructor.
     *
     * @param AvatarServiceInterface $avatarService Avatar service
     * @param TranslatorInterface    $translator    Translator
     */
    public function __construct(AvatarServiceInterface $avatarService, TranslatorInterface $translator, Security $security)
    {
        $this->avatarService = $avatarService;
        $this->translator = $translator;
        $this->security = $security;
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'avatar_create',
        methods: 'GET|POST'
    )]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if ($user->getAvatar()) {
            return $this->redirectToRoute(
                'avatar_edit',
            );
        }

        $avatar = new Avatar();
        $form = $this->createForm(
            AvatarType::class,
            $avatar,
            ['action' => $this->generateUrl('avatar_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->avatarService->create(
                $file,
                $avatar,
                $user
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'user/avatar/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/edit',
        name: 'avatar_edit',
        methods: 'GET|PUT|POST'
    )]
    public function edit(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $avatar = $user->getAvatar();

        if (!$avatar) {
            return $this->redirectToRoute('avatar_create');
        }

        $form = $this->createForm(
            AvatarType::class,
            $avatar,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('avatar_edit'),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->avatarService->update(
                $file,
                $avatar,
                $user
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );
            return $this->redirectToRoute('index');
        }

        return $this->render(
            'user/avatar/edit.html.twig',
            [
                'form' => $form->createView(),
                'avatar' => $avatar,
            ]
        );
    }

    #[Route(
        '/delete',
        name: 'avatar_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function delete(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $avatar = $user->getAvatar();

        if (!$avatar) {
            return $this->redirectToRoute('avatar_create');
        }

        $form = $this->createForm(
            FormType::class,
            $avatar,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('avatar_delete'),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var File $file */
            $this->avatarService->delete(
                $avatar,
                $user
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'user/avatar/delete.html.twig',
            [
                'form' => $form->createView(),
                'avatar' => $avatar,
            ]
        );
    }
}
