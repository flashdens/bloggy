<?php

namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TagServiceInterface
{
    public function findOneByTitle(string $title): ?Tag;

    public function saveTag(Tag $tag): void;

    public function deleteTag(Tag $tag): void;

    public function getPaginatedListOfAllTags(int $page): PaginationInterface;

    public function getPaginatedListOfPostsByTag(int $page, Tag $tag): PaginationInterface;

    public function includesTag(Tag $tag, array $tags): bool;

    public function findOneById(int $id): ?Tag;
}
