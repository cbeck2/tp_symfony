<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\PictureController;
use App\Repository\PictureRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ApiResource(
    types                : ['https://schema.org/MediaObject'],
    operations           : [
        new GetCollection(),
        new Get(),
        new Post(
            controller        : PictureController::class,
            openapiContext    : [
                                    'requestBody' => [
                                        'content' => [
                                            'multipart/form-data' => [
                                                'schema' => [
                                                    'type'       => 'object',
                                                    'properties' => [
                                                        'file'   => [
                                                            'type'   => 'string',
                                                            'format' => 'binary'
                                                        ],
                                                        'advert' => [
                                                            'type'   => 'int',
                                                            'format' => 'int'
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
            validationContext : ['groups' => ['Default', 'media_object_create']],
            deserialize       : false
                            ),
    ],
    normalizationContext : ['groups' => ['media_object:read']]
)]
#[Vich\Uploadable]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[Groups(['media_object:read'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "media_object", 
                           fileNameProperty: "path")]
    #[Assert\NotNull(groups: ['media_object_create'])]
    private ?File $file = null;


    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createAt = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?Advert $advert = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getAdvert(): ?Advert
    {
        return $this->advert;
    }

    public function setAdvert(?Advert $advert): self
    {
        $this->advert = $advert;

        return $this;
    }
}
