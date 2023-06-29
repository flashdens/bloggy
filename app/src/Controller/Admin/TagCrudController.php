<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\Type\TagType;
use App\Service\TagService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(
    '/admin/tag',
)]
#[IsGranted('ROLE_ADMIN')]
class TagCrudController extends AbstractController
{
    private TagService $tagService;

    private TranslatorInterface $translator;

    public function __construct(TagService $tagService, TranslatorInterface $translator)
    {
        $this->tagService = $tagService;
        $this->translator = $translator;
    }

    #[Route(
        name: 'admin_tag',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->tagService->getPaginatedListOfAll(
            $request->query->getInt('page', 1)
        );

        return $this->render('admin/tag/index.html.twig', ['pagination' => $pagination]);
    }

    #[Route(
        '/view/{id}',
        name: 'admin_view_tag',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function view(Request $request, Tag $tag): Response
    {
        $posts = $this->tagService->getPaginatedListOfPosts(
            $request->query->getInt('page', 1),
            $tag
        );

        return $this->render(
            'admin/tag/view.html.twig',
            [
                'posts' => $posts,
                'tag' => $tag,
            ]
        );
    }

    #[Route(
        '/edit/{id}',
        name: 'admin_edit_tag',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(
            TagType::class,
            $tag,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_edit_tag', ['id' => $tag->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->saveTag($tag);
            $this->addFlash(
                'success',
                $this->translator->trans('tag.edited_successfully')
            );

            return $this->redirectToRoute('admin_tag');
        }

        return $this->render('admin/tag/edit.html.twig', ['tag' => $tag, 'form' => $form->createView()]);
    }

    #[Route(
        '/delete/{id}',
        name: 'admin_delete_tag',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function delete(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(
            FormType::class,
            $tag,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('admin_delete_tag', ['id' => $tag->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->deleteTag($tag);
            $this->addFlash(
                'success',
                $this->translator->trans('tag.deleted_successfully')
            );

            return $this->redirectToRoute('admin_tag');
        }

        return $this->render(
            'admin/tag/delete.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }
}
