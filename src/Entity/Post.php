<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['post_read']],
    denormalizationContext: ['groups' => ['post_write']],
    order: ['dateCreation' => 'DESC'],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'genre' => 'exact',
        'creator' => 'exact',
    ]
)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
     #[Groups(['post_read', 'post_write', 'project_read', 'project_write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write', 'invite_read', 'invite_write'])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write'])]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write'])]
    private ?\DateTimeInterface $dateModified = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write'])]
    private ?User $creator = null;

    #[ORM\OneToOne(mappedBy: 'post', cascade: ['persist', 'remove'])]
    #[Groups(['post_read', 'post_write'])]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post_read', 'post_write'])]
    private ?Genre $genre = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Media::class)]
    #[Groups(['post_read', 'post_write'])]
    private Collection $media;

    public function __construct()
    {
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(?\DateTimeInterface $dateModified): static
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }


    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): static
    {
        // set the owning side of the relation if necessary
        if ($project->getPost() !== $this) {
            $project->setPost($this);
        }

        $this->project = $project;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setPost($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getPost() === $this) {
                $medium->setPost(null);
            }
        }

        return $this;
    }
}
