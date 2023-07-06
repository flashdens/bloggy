<?php

/**
 * Index controller. Welcome to my mine.
 */

namespace App\Controller;

use App\Form\Type\SearchType;
use App\Service\PostServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class IndexController.
 *
 * This controller handles the index page.
 */
class IndexController extends AbstractController
{
    /**
     * Post service.
     */
    private PostServiceInterface $postService;

    /**
     * IndexController constructor.
     *
     * @param PostServiceInterface $postService The post service
     */
    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Index action.
     *
     * @param Request $request The request object
     *
     * @return Response The response object
     */
    #[Route(
        name: 'index',
        methods: ['GET']
    )]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchType::class, [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);
        $filters = $this->getFilters($request);
        $posts = $this->postService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );

        return $this->render(
            'index/index.html.twig',
            [
                'form' => $form->createView(),
                'posts' => $posts,
            ]
        );
    }

    /**
     * Get filters from request.
     *
     * @param Request $request The request object
     *
     * @return array The filters extracted from the request
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');
        $filters['post'] = $request->query->getAlnum('search');

        return $filters;
    }
}
