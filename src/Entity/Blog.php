<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\EmptyController;
use App\Entity\Traits\Timestampable;
use App\Repository\BlogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: BlogRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-blog'],
    ],
    denormalizationContext: [
        'groups' => ['write-blog'],
    ],
    collectionOperations: [
        'get',
        'post' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'input_formats' => ["multipart"=>"multipart/form-data"]
        ],
    ],
    itemOperations: [
        'get',
        'put' => [
            'security' => "is_granted('ROLE_ADMIN')",
                    'method' => "POST",
                    'controller' => EmptyController::class,
                    'input_formats' => [
                        'multipart' => ["multipart/form-data"],
                    ],
                  'openapi_context' => [
                         'summary' => "Replaces the Blog resource"
                  ]
                 ],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]
class Blog
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-blog'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le titre du blog  ne peut être null.")]
    #[Assert\NotBlank(message:"Le titre du blog  ne peut être null.")]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "Le titre du blog  est au minimum 4 caractères",
        maxMessage: "Le titre du blog est au maximum 100 caractères"
    )]
    #[Groups(['list-blog','write-blog'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le lien du blog  ne peut être null.")]
    #[Assert\NotBlank(message:"Le lien du blog  ne peut être null.")]
    #[Groups(['list-blog','write-blog'])]
    private ?string $lien = null;


    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?User $user = null;

    #[Assert\File(
        maxSize:"2M",
        mimeTypes:["image/*"],
        maxSizeMessage:"La taille de l'image doit être inférieur à 2M.",
        mimeTypesMessage:"Le fichier n'est pas un fichier image."
    )]
    #[Groups(['write-blog'])]
    public ?File $imageFile = null;

    #[ORM\OneToOne(inversedBy: 'blog', cascade: ['persist', 'remove'])]
    #[Groups(['list-blog','write-blog'])]
    private ?Image $image = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
