<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class PostService implements PostServiceinterface
{
    private PostRepository $postRepository;

    private EntityManagerInterface $entityManager;
    private PaginatorInterface $paginator;

    private CommentRepository $commentRepository;

    public function __construct (PostRepository $postRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator, CommentRepository $commentRepository) {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->commentRepository = $commentRepository;
    }

    public function getPaginatedList (int $page) : PaginationInterface {
        return $this->paginator->paginate(
            $this->postRepository->queryAll(),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
    public function incrementViews (Post $post): void
    {
        $post->increment();
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function savePost(Post $post): void
    {
        if ($post->getId() == null) {
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

    public function deleteComments(Post $post) : void
    {
        $comments = $this->commentRepository->findBy(
            ['post' => $post]
        );
        foreach ($comments as $comment) {
            $this->commentRepository->remove($comment);
        }
    }
}

