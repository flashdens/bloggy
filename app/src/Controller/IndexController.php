<?php
/**
 * Hello controller.
 */

namespace App\Controller;

use App\Form\Type\SearchType;
use App\Service\PostService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class HelloController.
 */
#[Route(
    path: '/',
)]
class IndexController extends AbstractController
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    #[Route(
        name: 'index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->postService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        $form = $this->createForm(
            SearchType::class,
            [
                'method' => 'GET',
                'action' => $this->generateUrl('index'),
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('search', [
                'prompt' => $form->getData()['prompt'],
            ]);
        }

        return $this->render(
            'index/index.html.twig',
            [
                'pagination' => $pagination,
                'form' => $form->createView(),
            ]
        );
    }
}
