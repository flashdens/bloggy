<?php

namespace App\Service;

use App\Entity\Post;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PostServiceInterface
{
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface;

    public function incrementViews(Post $post): void;

    public function savePost(Post $post, ?UploadedFile $image): void;

    public function deletePost(Post $post): void;

    public function deleteComments(Post $post): void;

    public function findOneByTitle(string $title) : ?Post;

    function prepareFilters(array $filters): array;
}
