<?php


namespace App\Service;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
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
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListOfPosts(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->findBy(
                ['category' => $category]
            ),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function saveCategory (Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    public function deleteCategory (Category $category): void
    {
        $this->categoryRepository->remove($category);
    }

    public function canBeDeleted (Category $category) : bool {
        (bool)$this->categoryRepository->findBy(['category' => $category]);
    }

    public function findOneById(int $id): ?Category
    {
        // TODO: Implement findOneById() method.
    }
}