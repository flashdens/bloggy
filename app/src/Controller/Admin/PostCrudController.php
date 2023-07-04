<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\Type\PostType;
use App\Service\PostServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PostCrudController.
 */
#[Route('/admin/post')]
#[IsGranted('ROLE_ADMIN')]
class PostCrudController extends AbstractController
{
    private PostServiceInterface $postService;
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param PostServiceInterface $postService post service
     * @param TranslatorInterface  $translator  translator
     */
    public function __construct(PostServiceInterface $postService, TranslatorInterface $translator)
    {
        $this->postService = $postService;
        $this->translator = $translator;
    }

    /**
     * Show all posts.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'admin_post', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $pagination = $this->postService->getPaginatedList($request->query->getInt('page', 1));

        return $this->render('admin/post/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Edit post.
     *
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/edit/{id}',
        name: 'admin_edit_post',
        requirements: ['id' => '[0-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(
            PostType::class,
            $post,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_edit_post', ['id' => $post->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->savePost($post);
            $this->addFlash(
                'success',
                $this->translator->trans('post.edited_successfully')
            );

            return $this->redirectToRoute('admin_post');
        }

        return $this->render('admin/post/edit.html.twig', ['post' => $post, 'form' => $form->createView()]);
    }

    /**
     * Delete a post.
     *
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/delete/{id}',
        name: 'admin_delete_post',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET', 'POST']
    )]
    public function delete(Request $request, Post $post): Response
    {
        $form = $this->createForm(
            FormType::class,
            $post,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_delete_post', ['id' => $post->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->deletePost($post);
            $this->addFlash(
                'success',
                $this->translator->trans('post.deleted_successfully')
            );

            return $this->redirectToRoute('admin_post');
        }

        return $this->render(
            'admin/post/delete.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }
}
