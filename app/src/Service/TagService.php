<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService
 *
 * Service class for managing tags.
 */
class TagService implements TagServiceInterface
{
    private TagRepository $tagRepository;
    private PostRepository $postRepository;
    private PaginatorInterface $paginator;

    /**
     * TagService constructor.
     *
     * @param TagRepository      $tagRepository  The tag repository.
     * @param PostRepository     $postRepository The post repository.
     * @param PaginatorInterface $paginator      The paginator.
     */
    public function __construct(TagRepository $tagRepository, PostRepository $postRepository, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    /**
     * Find a tag by its title.
     *
     * @param string $title Tag title.
     *
     * @return Tag|null Tag entity or null if not found.
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneBy(['title' => $title]);
    }

    /**
     * Save a tag.
     *
     * @param Tag $tag Tag entity to save.
     */
    public function saveTag(Tag $tag): void
    {
        if (!$this->tagRepository->findBy(['title' => $tag->getTitle()])) {
            $tag->setCreatedAt(new \DateTimeImmutable());
        }
        $tag->setUpdatedAt(new \DateTimeImmutable());
        $this->tagRepository->save($tag);
    }

    /**
     * Delete a tag.
     *
     * @param Tag $tag Tag entity to delete.
     */
    public function deleteTag(Tag $tag): void
    {
        $posts = $tag->getPosts();
        foreach ($posts as $post) {
            $post->removeTag($tag);
        }
        $this->tagRepository->remove($tag);
    }

    /**
     * Get a paginated list of all tags.
     *
     * @param int $page Page number.
     *
     * @return PaginationInterface Paginated list of tags.
     */
    public function getPaginatedListOfAllTags(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->queryAll(),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get a paginated list of posts by tag.
     *
     * @param int $page Page number.
     * @param Tag $tag  Tag entity.
     *
     * @return PaginationInterface Paginated list of posts.
     */
    public function getPaginatedListOfPostsByTag(int $page, Tag $tag): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->findPostsByTag($tag),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Check if a tag is already included in an array.
     *
     * @param Tag   $tag  Tag entity to check.
     * @param Tag[] $tags Array of tags.
     *
     * @return bool True if the tag is included, false otherwise.
     */
    public function includesTag(Tag $tag, array $tags): bool
    {
        foreach ($tags as $tagInArray) {
            if ($tag->getTitle() === $tagInArray->getTitle()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find a tag by its ID.
     *
     * @param int $id Tag ID.
     *
     * @return Tag|null Tag entity or null if not found.
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }
}
