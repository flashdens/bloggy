<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PostService
 *
 * Service class for managing posts.
 */
class PostService implements PostServiceInterface
{
    /**
     * Post repository.
     *
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * Comment repository.
     *
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * Category service.
     *
     * @var CategoryServiceInterface
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Tag service.
     *
     * @var TagServiceInterface
     */
    private TagServiceInterface $tagService;

    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * File upload service.
     *
     * @var FileUploadServiceInterface
     */
    private FileUploadServiceInterface $fileUploadService;

    /**
     * PostService constructor.
     *
     * @param PostRepository             $postRepository    The post repository.
     * @param CommentRepository          $commentRepository The comment repository.
     * @param CategoryServiceInterface   $categoryService   The category service.
     * @param EntityManagerInterface     $entityManager     The entity manager.
     * @param TagServiceInterface        $tagService        The tag service.
     * @param PaginatorInterface         $paginator         The paginator.
     * @param FileUploadServiceInterface $fileUploadService The file upload service.
     */
    public function __construct(PostRepository $postRepository, CommentRepository $commentRepository, CategoryServiceInterface $categoryService, EntityManagerInterface $entityManager, TagServiceInterface $tagService, PaginatorInterface $paginator, FileUploadServiceInterface $fileUploadService)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Get a paginated list of posts.
     *
     * @param int   $page    Page number.
     * @param array $filters Filters for querying posts.
     *
     * @return PaginationInterface Paginated list of posts.
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->postRepository->queryAll($filters),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Increment the view count of a post.
     *
     * @param Post $post Post entity.
     */
    public function incrementViews(Post $post): void
    {
        $post->increment();
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    /**
     * Save a post.
     *
     * @param Post              $post  Post entity.
     * @param UploadedFile|null $image Uploaded file for the post's image.
     */
    public function savePost(Post $post, ?UploadedFile $image): void
    {
        if ($image) {
            $file = $this->fileUploadService->upload($image);
            $post->setImage($file);
        } else {
            $post->setImage('default.jpeg');
        }

        if (null === $post->getId()) {
            $post->setPublished(new \DateTimeImmutable());
        }
        $post->setEdited(new \DateTimeImmutable());

        $this->postRepository->save($post);
    }

    /**
     * Delete a post.
     *
     * @param Post $post Post entity to delete.
     */
    public function deletePost(Post $post): void
    {
        $this->deleteComments($post);
        $this->postRepository->delete($post);
    }

    /**
     * Delete comments associated with a post.
     *
     * @param Post $post Post entity.
     */
    public function deleteComments(Post $post): void
    {
        $comments = $this->commentRepository->findBy(['post' => $post]);
        foreach ($comments as $comment) {
            $this->commentRepository->remove($comment);
        }
    }

    /**
     * Find a post by its title.
     *
     * @param string $title Post title.
     *
     * @return Post|null Post entity or null if not found.
     */
    public function findOneByTitle(string $title): ?Post
    {
        return $this->postRepository->findOneByTitle($title);
    }

    /**
     * Prepare filters for querying posts.
     *
     * @param array $filters Filters array.
     *
     * @return array Prepared filters array.
     */
    public function prepareFilters(array $filters): array
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

        if (!empty($filters['post'])) {
            $post = $this->postRepository->findOneByTitle($filters['post']['search']);
            if (null !== $post) {
                $resultFilters['post'] = $post;
            }
        }

        return $resultFilters;
    }
}
