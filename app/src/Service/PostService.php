<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostService implements PostServiceInterface
{

    private PostRepository $postRepository;

    private CommentRepository $commentRepository;

    private CategoryServiceInterface $categoryService;

    private TagServiceInterface $tagService;

    private EntityManagerInterface $entityManager;

    private PaginatorInterface $paginator;

    private FileUploadServiceInterface $fileUploadService;


    public function __construct(PostRepository $postRepository,  CommentRepository $commentRepository,
                                CategoryServiceInterface $categoryService,
                                EntityManagerInterface $entityManager, TagServiceInterface $tagService,
                                PaginatorInterface $paginator, FileUploadServiceInterface $fileUploadService)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->fileUploadService = $fileUploadService;
    }

    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->postRepository->queryAll($filters), // search returns all posts when it should return none. idk why
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

    public function savePost(Post $post, ?UploadedFile $image): void
    {
        if ($image) {
            $file = $this->fileUploadService->upload($image);
            $post->setImage($file);
        }
        else {
            $post->setImage('default.jpeg');
        }

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

    public function findOneByTitle(string $title) : ?Post
    {
       return $this->postRepository->findOneByTitle($title);
    }

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
