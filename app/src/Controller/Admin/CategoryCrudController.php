<?php

namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Service\CategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


#[Route(
    '/admin/category',
)]
#[IsGranted('ROLE_ADMIN')]

class CategoryCrudController extends AbstractController
{

    private CategoryService $categoryService;

    private TranslatorInterface $translator;


    public function __construct(CategoryService $postService, TranslatorInterface $translator) {
        $this->categoryService = $postService;
        $this->translator = $translator;
    }

    #[Route(
        name: 'admin_category',
        methods: 'GET'
    )]
    public function index (Request $request) : Response
    {
        $pagination = $this->categoryService->getPaginatedList(
            $request->query->getInt('page', 1)
        );
        return $this->render('admin/category/change_password.html.twig', ['pagination' => $pagination]);
    }

    #[Route(
        '/add',
        name: 'admin_add_category',
        methods: 'GET|POST'
    )]
    public function create (Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(
            CategoryType::class,
            $category,
            ['action' => $this->generateUrl('admin_add_category')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->saveCategory($category);
            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );
            return $this->redirectToRoute('admin_category');
        }
        return $this->render('admin/category/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route(
        '/edit/{id}',
        name: 'admin_view_category',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function view (Request $request, Category $category) : Response
    {
        $posts = $this->categoryService->getPaginatedListOfPosts(
            $request->query->getInt('page', 1),
            $category
        );
        return $this->render('admin/category/view.html.twig', ['category' => $category, 'posts' => $posts]);
    }

    #[Route(
        '/edit/{id}',
        name: 'admin_edit_category',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function edit (Request $request, Category $category) : Response
    {
        $form = $this->createForm(
            CategoryType::class,
            $category,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_edit_category', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->saveCategory($category);
            $this->addFlash('success',
                $this->translator->trans('category.edited_successfully'));
            return $this->redirectToRoute('admin_category');
        }
        return $this->render('admin/post/edit.html.twig', ['category' => $category, 'form' => $form->createView()]);
    }

    #[Route(
        '/delete/{id}',
        name: 'admin_delete_category',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function delete (Request $request, Category $category) : Response
    {
        $form = $this->createForm(
            FormType::class,
            $category,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_delete_category', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('admin_category');
        }
        $this->categoryService->deleteCategory($category);
        $this->addFlash('success',
            $this->translator->trans('category.deleted_successfully')
        );
        return $this->redirectToRoute('admin_category');
    }

}
