<?php
/**
 * Hello controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HelloController.
 */
#[Route(
    path: '/',
)]
class IndexController extends AbstractController
{
    /**
     * Index action.
     *
     * @param string $name User input
     *
     * @return Response HTTP response
     */
    #[Route(
        path: '/index'
    )]
    public function index(): Response
    {
        return $this->render(
            'index.html.twig'
        );
    }
}
