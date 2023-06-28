<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\Type\CommentType;
use App\Form\Type\PostType;
use App\Service\CommentService;
use App\Service\PostService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/post',
)]

class PostController extends AbstractController
{

    private PostService $postService;
    private CommentService $commentService;
    private TokenStorageInterface $tokenStorage;


    public function __construct(PostService $postService, CommentService $commentService, TokenStorageInterface $tokenStorage)
    {
        $this->postService = $postService;
        $this->commentService = $commentService;
        $this->tokenStorage = $tokenStorage;
    }

    #[Route(
        '/{id}',
        name: 'view_post',
        requirements: ['id' => '[0-9]\d*'],
        methods: 'GET|POST'
    )]
    public function view(Request $request, Post $post): Response
    {
        $this->postService->incrementViews($post);
        $pagination = $this->commentService->getPaginatedList($request->query->getInt('page', 1), $post);

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
                'form' => $form->createView()
            ]
        );
    }
    #[Route(
        '/create',
        name: 'create_post',
        methods: 'GET|POST',
    )]
    public function createPost (Request $request) : Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->savePost($post);
            return $this->redirectToRoute('index' );
        }
            return $this->render('post/create.html.twig',
                ['form' => $form->createView()]);
    }
}

