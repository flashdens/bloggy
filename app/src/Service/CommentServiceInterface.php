<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CommentServiceInterface
{
    public function getPaginatedList(int $page, Post $post) : PaginationInterface;

    public function saveComment(Comment $comment) : void;
}