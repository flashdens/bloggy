<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Tag;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use DateTimeImmutable;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class TagService implements TagServiceInterface
{
    private TagRepository $tagRepository;

    private PostRepository $postRepository;

    private PaginatorInterface $paginator;

    public function __construct(TagRepository $tagRepository, PostRepository $postRepository, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneBy(['title' => $title]);
    }

    public function saveTag(Tag $tag): void
    {
        if (!$this->tagRepository->findBy(['title' => $tag->getTitle()])) {
            $tag->setCreatedAt(new DateTimeImmutable());
        }
        $tag->setUpdatedAt(new DateTimeImmutable());
        $this->tagRepository->save($tag);
    }

    public function deleteTag(Tag $tag): void
    {
        $posts = $tag->getPosts();
        foreach ($posts as $post) {
            $post->removeTag($tag);
        }
        $this->tagRepository->remove($tag);
    }

    public function getPaginatedListOfAllTags(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->queryAll(),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListOfPostsByTag(int $page, Tag $tag): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->findPostsByTag($tag),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function includesTag(Tag $tag, array $tags): bool
    {
        foreach ($tags as $tag_in_array) {
            if ($tag->getTitle() == $tag_in_array->getTitle()) {
                return true;
            }
        }
        return false;
    }

    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id); // findOneById not found but it works for some reason???
    }
}
