<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Tag;
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

    public function __construct (TagRepository $tagRepository, PostRepository $postRepository, PaginatorInterface $paginator) {
        $this->tagRepository = $tagRepository;
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }

    public function saveTag (Tag $tag): void
    {
        if ($tag->getId() == null) {
            $tag->setCreatedAt(new DateTimeImmutable());
        }
        $this->tagRepository->save($tag);
    }

    public function deleteTag (Tag $tag) : void
    {
        $posts = $tag->getPosts();
        foreach ($posts as $post) {
            $post->removeTag($tag);
        }
        $this->tagRepository->remove($tag);
    }

    public function getPaginatedListOfAll (int $page) : PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->queryAll(),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListOfPosts (int $page, Tag $tag) : PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->findPostsByTag($tag),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}