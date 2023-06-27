<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\TagRepository;
use DateTimeImmutable;

class TagService implements TagServiceInterface
{
    private TagRepository $tagRepository;

    public function __construct (TagRepository $tagRepository) {
        $this->tagRepository = $tagRepository;
    }

    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }

    public function save(Tag $tag): void
    {
        if ($tag->getId() == null) {
            $tag->setCreatedAt(new DateTimeImmutable());
        }
        $this->tagRepository->save($tag);
    }
}