<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class PostService implements PostServiceinterface
{
    private PostRepository $postRepository;
    private CommentRepository $commentRepository;

    private CategoryServiceInterface $categoryService;

    private TagServiceInterface $tagService;

    private EntityManagerInterface $entityManager;

    private PaginatorInterface $paginator;

    public function __construct(PostRepository $postRepository,  CategoryServiceInterface $categoryService,
                                EntityManagerInterface $entityManager, TagServiceInterface $tagService,
                                PaginatorInterface $paginator, CommentRepository $commentRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->postRepository->queryAll($filters),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function incrementViews(Post $post): void
    {
        $post->increment();
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function savePost(Post $post): void
    {
        if (null == $post->getId()) {
            $post->setPublished(new DateTimeImmutable());
        }
        $post->setEdited(new DateTimeImmutable());
        $this->postRepository->save($post);
    }

    public function deletePost(Post $post): void
    {
        $this->deleteComments($post);
        $this->postRepository->delete($post);
    }

    public function deleteComments(Post $post): void
    {
        $comments = $this->commentRepository->findBy(
            ['post' => $post]
        );
        foreach ($comments as $comment) {
            $this->commentRepository->remove($comment);
        }
    }

    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (!empty($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }
//
//        if (!empty($filters['title'])) {
//            $title = $this->
//        }

        return $resultFilters;
    }
}
