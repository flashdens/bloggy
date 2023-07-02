<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CategoryService implements CategoryServiceInterface
{
    private CategoryRepository $categoryRepository;

    private PostRepository $postRepository;
    private PaginatorInterface $paginator;

    public function __construct(CategoryRepository $categoryRepository, PostRepository $postRepository, PaginatorInterface $paginator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListOfPosts(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->findBy(
                ['category' => $category]
            ),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function saveCategory(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    public function deleteCategory(Category $category): void
    {
        $posts = $this->postRepository->findBy(
            ['category' => $category]
        );
        foreach ($posts as $post) {
            $post->setCategory(null);
            $this->postRepository->save($post);
        }
        $this->categoryRepository->remove($category);
    }

    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }
}
