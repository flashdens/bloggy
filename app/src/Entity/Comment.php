<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
/**
 * Comment entity.
 */
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $published = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * Get the ID of the comment.
     *
     * @return int|null The ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the published date of the comment.
     *
     * @return \DateTimeInterface|null The published date
     */
    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    /**
     * Set the published date of the comment.
     *
     * @param \DateTimeInterface $published The published date
     *
     * @return $this
     */
    public function setPublished(\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get the content of the comment.
     *
     * @return string|null The content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the content of the comment.
     *
     * @param string $content The content
     *
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the post associated with the comment.
     *
     * @return Post|null The post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * Set the post associated with the comment.
     *
     * @param Post|null $post The post
     *
     * @return $this
     */
    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get the author of the comment.
     *
     * @return User|null The author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Set the author of the comment.
     *
     * @param User|null $author The author
     *
     * @return $this
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
