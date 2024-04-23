<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['profil_read']],
    denormalizationContext: ['groups' => ['profil_write']],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'userId' => 'exact',
    ]
)]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['profil_read', 'profil_write', 'user_read', 'user_write', 'post_read', 'post_write'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['profil_read', 'profil_write'])]
    private ?string $biography = null;

    #[ORM\ManyToOne(inversedBy: 'profils')]
    #[Groups(['profil_read', 'profil_write', 'post_read', 'post_write'])]
    private ?Filiere $filiere = null;

    #[ORM\OneToOne(inversedBy: 'profil', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['profil_read', 'profil_write'])]
    private ?User $userId = null;

    #[ORM\ManyToMany(targetEntity: Skill::class)]
    #[Groups(['profil_read', 'profil_write'])]
    private Collection $skills;

    #[ORM\OneToMany(mappedBy: 'profil', targetEntity: Contact::class, orphanRemoval: true)]
    #[Groups(['profil_read', 'profil_write'])]
    private Collection $contacts;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): static
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(User $userId): static
    {
        $this->userId = $userId;

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
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setProfil($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getProfil() === $this) {
                $contact->setProfil(null);
            }
        }

        return $this;
    }

    

    
}
