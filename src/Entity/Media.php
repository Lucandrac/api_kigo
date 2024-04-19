<?php

namespace App\Entity;

use App\Entity\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MediaRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\MediaImageController;
use ApiPlatform\Metadata\Post as PostData;
use App\Controller\CreateMediaObjectAction;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ApiResource]
#[Get()]
#[GetCollection()]
#[PostData(
    denormalizationContext: ['groups' => ['media_write']],
    inputFormats: ['multipart' => ['multipart/form-data']],
    controller: CreateMediaObjectAction::class,
)]
#[Vich\Uploadable]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post_read', 'post_write', 'project_read', 'project_write', 'media_write'])]
    private ?string $url = null;

    #[Vich\UploadableField(mapping: 'media', fileNameProperty: 'url')]
    #[Groups(['media_write'])]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255)]
    #[Groups(['media_write'])]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['media_write'])]
    private ?Post $post = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
    }
}
