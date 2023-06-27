<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'posts')]

class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $published = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $edited = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, fetch: 'EXTRA_LAZY')]
    private Collection $comments;

    #[ORM\ManyToOne (targetEntity: Category::class, fetch: 'EXTRA_LAZY', inversedBy: 'posts')]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: true)]
    private ?Category $category = null;

    #[ORM\Column(length: 64)]
    private ?string $title = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?int $views = 0;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts', fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getEdited(): ?DateTimeInterface
    {
        return $this->edited;
    }

    public function setEdited(DateTimeInterface $edited): self
    {
        $this->edited = $edited;

        return $this;
    }


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function increment() : self
    {
        $this->views = $this->views + 1;
        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
