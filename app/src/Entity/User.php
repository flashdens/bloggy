<?php

namespace App\Entity;

use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'email_idx', columns: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
/**
 * User entity.
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $isBanned = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => '2021-01-01 01:01:01'])]
    private ?\DateTimeInterface $joined = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Avatar $avatar = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Get the ID of the user.
     *
     * @return int|null The ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the email of the user.
     *
     * @return string|null The email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the email of the user.
     *
     * @param string $email The email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @return string userId
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return string userId
     *
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return array $roles userRoles
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRole::ROLE_USER->value;

        return array_unique($roles);
    }

    /**
     * Set the roles of the user.
     *
     * @param array $roles The roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string $password
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the password of the user.
     *
     * @param string $password The password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     *
     * @return string|null salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Check if the user is banned.
     *
     * @return bool|null True if banned, false otherwise
     */
    public function isBanned(): ?bool
    {
        return $this->isBanned;
    }

    /**
     * Set the banned status of the user.
     *
     * @param bool $isBanned True to ban, false to unban
     *
     * @return $this
     */
    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    /**
     * Get the join date of the user.
     *
     * @return \DateTimeInterface|null The join date
     */
    public function getJoined(): ?\DateTimeInterface
    {
        return $this->joined;
    }

    /**
     * Set the join date of the user.
     *
     * @param \DateTimeInterface $joined The join date
     *
     * @return $this
     */
    public function setJoined(\DateTimeInterface $joined): self
    {
        $this->joined = $joined;

        return $this;
    }

    /**
     * Get the comments written by the user.
     *
     * @return Collection<int, Comment> The comments
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Add a comment written by the user.
     *
     * @param Comment $comment The comment to add
     *
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    /**
     * Remove a comment written by the user.
     *
     * @param Comment $comment The comment to remove
     *
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * Get the avatar of the user.
     *
     * @return Avatar|null The avatar
     */
    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    /**
     * Set the avatar of the user.
     *
     * @param Avatar|null $avatar The avatar
     *
     * @return static this
     */
    public function setAvatar(?Avatar $avatar): static
    {
        if ($avatar->getUser() !== $this) {
            $avatar->setUser($this);
        }

        $this->avatar = $avatar;

        return $this;
    }
}
