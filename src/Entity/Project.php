<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ["project_read"]],
    denormalizationContext: ['groups' => ["project_write"]],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'participant.id' => 'exact',
    ]
)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write', 'invite_read', 'invite_write'])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'project', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['project_read', 'project_write', 'invite_read', 'invite_write'])]
    private ?Post $post = null;

    #[ORM\Column]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write'])]
    private ?bool $isOpen = null;

    #[ORM\Column]
    #[Groups(['project_read', 'project_write'])]
    private ?bool $isFinished = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projects')]
    #[Groups(['project_read', 'project_write'])]
    private Collection $participant;

    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'projects')]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write'])]
    private Collection $skills;

    #[ORM\ManyToMany(targetEntity: Filiere::class, inversedBy: 'projects')]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write'])]
    private Collection $filieres;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Invite::class)]
    #[Groups(['project_read', 'project_write'])]
    private Collection $invites;

    #[ORM\ManyToOne]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write'])]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Message::class)]
    #[Groups(['project_read', 'project_write'])]
    private Collection $messages;

    public function __construct()
    {
        $this->participant = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->filieres = new ArrayCollection();
        $this->invites = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function isIsOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): static
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    public function isIsFinished(): ?bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): static
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    /**
     * @return Collection<int, Profil>
     */
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participant->contains($participant)) {
            $this->participant->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participant->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection<int, Filiere>
     */
    public function getFilieres(): Collection
    {
        return $this->filieres;
    }

    public function addFiliere(Filiere $filiere): static
    {
        if (!$this->filieres->contains($filiere)) {
            $this->filieres->add($filiere);
        }

        return $this;
    }

    public function removeFiliere(Filiere $filiere): static
    {
        $this->filieres->removeElement($filiere);

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
            $invite->setProject($this);
        }

        return $this;
    }

    public function removeInvite(Invite $invite): static
    {
        if ($this->invites->removeElement($invite)) {
            // set the owning side to null (unless already changed)
            if ($invite->getProject() === $this) {
                $invite->setProject(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setProject($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getProject() === $this) {
                $message->setProject(null);
            }
        }

        return $this;
    }
}
