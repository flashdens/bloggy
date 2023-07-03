<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Category service class.
 */
class CategoryService implements CategoryServiceInterface
{
    private CategoryRepository $categoryRepository;
    private PostRepository $postRepository;
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param CategoryRepository $categoryRepository Category repository
     * @param PostRepository     $postRepository     Post repository
     * @param PaginatorInterface $paginator          Paginator interface
     */
    public function __construct(CategoryRepository $categoryRepository, PostRepository $postRepository, PaginatorInterface $paginator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get a paginated list of categories.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list of categories
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get a paginated list of posts for a category.
     *
     * @param int      $page     Page number
     * @param Category $category Category entity
     *
     * @return PaginationInterface Paginated list of posts for the category
     */
    public function getPaginatedListOfPosts(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->findBy(['category' => $category]),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save a category.
     *
     * @param Category $category Category entity
     */
    public function saveCategory(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete a category.
     *
     * @param Category $category Category entity
     */
    public function deleteCategory(Category $category): void
    {
        $posts = $this->postRepository->findBy(['category' => $category]);
        foreach ($posts as $post) {
            $post->setCategory(null);
            $this->postRepository->save($post);
        }
        $this->categoryRepository->remove($category);
    }

    /**
     * Find a category by ID.
     *
     * @param int $id Category ID
     *
     * @return Category|null Category entity or null if not found
     */
    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }
}
