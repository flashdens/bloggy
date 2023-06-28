<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $keyword = $form->getData()['keyword'];

            // Perform your search logic here
            // Example: query the database for results based on the keyword

            // Replace the following line with your search logic
            $results = ['Result 1', 'Result 2', 'Result 3'];

            return $this->render('search/results.html.twig', [
                'keyword' => $keyword,
                'results' => $results,
            ]);
        }

        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
