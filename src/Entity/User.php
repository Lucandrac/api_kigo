<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    normalizationContext: ['groups' => ['user_read']],
    denormalizationContext: ['groups' => ['user_write']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['profil_read', 'profil_write', 'project_read', 'project_write', 'invite_read', 'invite_write', 'user_read', 'user_write'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user_read', 'user_write'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user_read', 'user_write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['profil_read', 'profil_write', 'project_read', 'project_write', 'invite_read', 'invite_write', 'user_read', 'user_write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['profil_read', 'profil_write', 'project_read', 'project_write', 'invite_read', 'invite_write', 'user_read', 'user_write'])]
    private ?string $firstName = null;

    #[ORM\Column]
    private ?bool $isAdmin = null;

    #[ORM\OneToOne(mappedBy: 'userId', cascade: ['persist', 'remove'])]
    #[Groups(['user_read', 'user_write'])]
    private ?Profil $profil = null;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Post::class)]
    #[Groups(['profil_read', 'profil_write'])]
    private Collection $posts;


    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'participant')]
    #[Groups(['profil_read', 'profil_write'])]
    private Collection $projects;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Invite::class)]
    private Collection $invites;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups(['profil_read', 'profil_write', 'user_read', 'user_write'])]
    private ?Avatar $avatar = null;
    
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->invites = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function isIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(Profil $profil): static
    {
        // set the owning side of the relation if necessary
        if ($profil->getUserId() !== $this) {
            $profil->setUserId($this);
        }

        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCreator($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCreator() === $this) {
                $post->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addParticipant($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Invite>
     */
    public function getInvites(): Collection
    {
        return $this->invites;
    }

    public function addInvite(Invite $invite): static
    {
        if (!$this->invites->contains($invite)) {
            $this->invites->add($invite);
            $invite->setUserId($this);
        }

        return $this;
    }

    public function removeInvite(Invite $invite): static
    {
        if ($this->invites->removeElement($invite)) {
            // set the owning side to null (unless already changed)
            if ($invite->getUserId() === $this) {
                $invite->setUserId(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(?Avatar $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }
}
