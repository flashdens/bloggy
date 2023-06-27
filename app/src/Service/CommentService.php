<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CommentService implements CommentServiceInterface
{
    private CommentRepository $commentRepository;

    private PostRepository $postRepository;
    private PaginatorInterface $paginator;


    public function __construct (CommentRepository $commentRepository, PostRepository $postRepository, PaginatorInterface $paginator) {
        $this->commentRepository = $commentRepository;
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    public function getPaginatedList (int $page, Post $post) : PaginationInterface {
        return $this->paginator->paginate(
            $this->commentRepository->findBy(
                ['post' => $post]
            ),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListOfAllComments (int $page) : PaginationInterface {
        return $this->paginator->paginate(
            $this->commentRepository->queryAll(),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function saveComment(Comment $comment): void
    {
        if ($comment->getId() == null) {
            $comment->setPublished(new DateTimeImmutable());
        }
        $this->commentRepository->save($comment);
    }

    public function deleteComment(Comment $comment) : void
    {
        $this->commentRepository->remove($comment);
    }
}