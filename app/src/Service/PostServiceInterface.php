<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface PostServiceInterface
{
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface;

    public function incrementViews(Post $post): void;

    public function savePost(Post $post): void;

    public function deletePost(Post $post): void;

    public function deleteComments(Post $post): void;

    public function findOneByTitle(string $title) : ?Post;

    function prepareFilters(array $filters): array;
}
