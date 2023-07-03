<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\Type\CommentType;
use App\Form\Type\PostType;
use App\Service\CommentServiceInterface;
use App\Service\PostServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 */

#[Route('/post')]
class PostController extends AbstractController
{
    private PostServiceInterface $postService;
    private CommentServiceInterface $commentService;
    private TokenStorageInterface $tokenStorage;

    /**
     * PostController constructor.
     *
     * @param PostServiceInterface    $postService    Post service interface
     * @param CommentServiceInterface $commentService Comment service interface
     * @param TokenStorageInterface   $tokenStorage   Token storage
     */
    public function __construct(PostServiceInterface $postService, CommentServiceInterface $commentService, TokenStorageInterface $tokenStorage)
    {
        $this->postService = $postService;
        $this->commentService = $commentService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * View a post.
     *
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'view_post',
        requirements: ['id' => '\d+'],
        methods: ['GET', 'POST']
    )]
    public function view(Request $request, Post $post): Response
    {
        $this->postService->incrementViews($post);
        $pagination = $this->commentService->getPaginatedList(
            $request->query->getInt('page', 1),
            $post
        );

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $comment->setAuthor($this->tokenStorage->getToken()->getUser());
            $this->commentService->saveComment($comment);

            return $this->redirectToRoute('view_post', ['id' => $post->getId()]);
        }

        return $this->render(
            'post/index.html.twig',
            [
                'post' => $post,
                'pagination' => $pagination,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Create a post.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'create_post',
        methods: ['GET', 'POST']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            $this->postService->savePost($post, $image);

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'post/create.html.twig',
            ['form' => $form->createView()]
        );
    }
}
