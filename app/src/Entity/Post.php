<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'posts')]
/**
 * Post entity.
 */
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $published = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $edited = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, fetch: 'EXTRA_LAZY')]
    private Collection $comments;

    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EXTRA_LAZY', inversedBy: 'posts')]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: true)]
    private ?Category $category = null;

    #[ORM\Column(length: 64)]
    public ?string $title = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $views = 0;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts', fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $tags;

    #[ORM\Column(length: 191)]
    private ?string $image = null;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Get the ID of the post.
     *
     * @return int|null the ID of the post
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the content of the post.
     *
     * @return string|null the content of the post
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the content of the post.
     *
     * @param string $content the content of the post
     *
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the published datetime of the post.
     *
     * @return \DateTimeInterface|null the published datetime of the post
     */
    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    /**
     * Set the published datetime of the post.
     *
     * @param \DateTimeInterface $published the published datetime of the post
     *
     * @return $this
     */
    public function setPublished(\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get the edited datetime of the post.
     *
     * @return \DateTimeInterface|null the edited datetime of the post
     */
    public function getEdited(): ?\DateTimeInterface
    {
        return $this->edited;
    }

    /**
     * Set the edited datetime of the post.
     *
     * @param \DateTimeInterface $edited the edited datetime of the post
     *
     * @return $this
     */
    public function setEdited(\DateTimeInterface $edited): self
    {
        $this->edited = $edited;

        return $this;
    }

    /**
     * Get the category of the post.
     *
     * @return Category|null the category of the post
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Set the category of the post.
     *
     * @param Category|null $category the category of the post
     *
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the title of the post.
     *
     * @return string|null the title of the post
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the title of the post.
     *
     * @param string $title the title of the post
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the number of views for the post.
     *
     * @return int|null the number of views for the post
     */
    public function getViews(): ?int
    {
        return $this->views;
    }

    /**
     * Set the number of views for the post.
     *
     * @param int $views the number of views for the post
     *
     * @return $this
     */
    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Increment the number of views for the post.
     *
     * @return $this
     */
    public function increment(): self
    {
        $this->views = $this->views + 1;

        return $this;
    }

    /**
     * Get the tags associated with the post.
     *
     * @return Collection<int, Tag> the tags associated with the post
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add a tag to the post.
     *
     * @param Tag $tag the tag to add
     *
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove a tag from the post.
     *
     * @param Tag $tag the tag to remove
     *
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * Get the image filename of the post.
     *
     * @return string|null the image filename of the post
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the image filename of the post.
     *
     * @param string $image the image filename of the post
     *
     * @return $this
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
