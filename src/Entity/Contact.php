<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ApiResource]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        "profil" => "exact",
    ]
)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['profil_read', 'profil_write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['profil_read', 'profil_write'])]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['profil_read', 'profil_write'])]
    private ?TypeContact $type = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profil $profil = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?TypeContact
    {
        return $this->type;
    }

    public function setType(?TypeContact $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): static
    {
        $this->profil = $profil;

        return $this;
    }
}
