<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CommentServiceInterface.
 */
interface CommentServiceInterface
{
    /**
     * Get a paginated list of comments for a specific post.
     *
     * @param int  $page Page number
     * @param Post $post Post entity
     *
     * @return PaginationInterface Paginated list of comments
     */
    public function getPaginatedList(int $page, Post $post): PaginationInterface;

    /**
     * Save a comment.
     *
     * @param Comment $comment Comment entity
     */
    public function saveComment(Comment $comment): void;
}
