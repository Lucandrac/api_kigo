<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\InviteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: InviteRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['invite_read']],
    denormalizationContext: ['groups' => ['invite_write']],
    order: ['dateCreated' => 'DESC'],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'project' => 'exact',
    ],
)]
#[ApiFilter(BooleanFilter::class, properties: ['isActive'])]
class Invite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['invite_read', 'invite_write', 'project_read', 'project_write'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invites')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write', 'invite_read', 'invite_write'])]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: 'invites')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['invite_read', 'invite_write'])]
    private ?Project $project = null;

    #[ORM\Column]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write', 'invite_read', 'invite_write'])]
    private ?bool $isActive = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['invite_read', 'invite_write', 'project_read', 'project_write'])]
    private ?\DateTimeInterface $dateCreated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }
}
