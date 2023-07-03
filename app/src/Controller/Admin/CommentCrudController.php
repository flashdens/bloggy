<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Form\Type\CommentType;
use App\Service\CommentServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommentCrudController.
 */
#[Route('/admin/comment')]
#[IsGranted('ROLE_ADMIN')]
class CommentCrudController extends AbstractController
{
    /**
     * Comment service.
     *
     * @var CommentServiceInterface
     */
    private CommentServiceInterface $commentService;

    /**
     * Translator.
     *
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * AdminController constructor.
     *
     * @param CommentServiceInterface $commentService The comment service
     * @param TranslatorInterface     $translator     The translator
     */
    public function __construct(CommentServiceInterface $commentService, TranslatorInterface $translator)
    {
        $this->commentService = $commentService;
        $this->translator = $translator;
    }

    /**
     * Show all comments.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'admin_comment', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $pagination = $this->commentService->getPaginatedListOfAllComments($request->query->getInt('page', 1));

        return $this->render('admin/comment/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * View a comment.
     *
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/view/{id}',
        name: 'admin_view_comment',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function view(Comment $comment): Response
    {
        return $this->render('admin/comment/view.html.twig', ['comment' => $comment]);
    }

    /**
     * Edit a comment.
     *
     * @param Request $request HTTP request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/edit/{id}',
        name: 'admin_edit_comment',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(
            CommentType::class,
            $comment,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_edit_comment', ['id' => $comment->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->saveComment($comment);
            $this->addFlash(
                'success',
                $this->translator->trans('comment.edited_successfully')
            );

            return $this->redirectToRoute('admin_comment');
        }

        return $this->render('admin/comment/edit.html.twig', ['comment' => $comment, 'form' => $form->createView()]);
    }

    /**
     * Delete a comment.
     *
     * @param Request $request HTTP request
     * @param Comment $comment Comment entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/delete/{id}',
        name: 'admin_delete_comment',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function delete(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(
            FormType::class,
            $comment,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_delete_comment', ['id' => $comment->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->deleteComment($comment);
            $this->addFlash(
                'success',
                $this->translator->trans('comment.deleted_successfully')
            );

            return $this->redirectToRoute('admin_comment');
        }

        return $this->render(
            'admin/comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }
}
