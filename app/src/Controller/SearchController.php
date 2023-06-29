<?php

namespace App\Controller;

use App\Form\Type\SearchType;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/search'
)]
class SearchController extends AbstractController
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    #[Route(
        '/{prompt}',
        name: 'search',
        requirements: ['prompt' => '^[A-Za-z0-9]*$'],
        methods: 'GET'
    )]
    public function index(Request $request, string $prompt): Response
    {
        $results = $this->postService->search(
            $request->query->getInt('page', 1),
            $prompt
        );
        $form = $this->createForm(
            SearchType::class,
            [
                'method' => 'GET',
                'action' => $this->generateUrl('search', ['prompt' => $prompt]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('search', [
                'prompt' => $form->getData()['prompt'],
                'results' => $results,
            ]);
        }

        return $this->render(
            'search/change_password.html.twig',
            [
                'form' => $form->createView(),
                'results' => $results,
            ]
        );
    }
}
