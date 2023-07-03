<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use DateTimeImmutable;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Comment service.
 */
class CommentService implements CommentServiceInterface
{
    /**
     * Comment repository.
     *
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * CommentService constructor.
     *
     * @param CommentRepository  $commentRepository The comment repository
     * @param PaginatorInterface $paginator         The paginator
     */
    public function __construct(CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get a paginated list of comments for a specific post.
     *
     * @param int  $page Page number
     * @param Post $post Post entity
     *
     * @return PaginationInterface Paginated list of comments
     */
    public function getPaginatedList(int $page, Post $post): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->findBy(
                ['post' => $post]
            ),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get a paginated list of all comments.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list of comments
     */
    public function getPaginatedListOfAllComments(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->findAll(),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save a comment.
     *
     * @param Comment $comment Comment entity to save
     */
    public function saveComment(Comment $comment): void
    {
        if (null == $comment->getId()) {
            $comment->setPublished(new DateTimeImmutable());
        }
        $this->commentRepository->save($comment);
    }

    /**
     * Delete a comment.
     *
     * @param Comment $comment Comment entity to delete
     */
    public function deleteComment(Comment $comment): void
    {
        $this->commentRepository->remove($comment);
    }
}
