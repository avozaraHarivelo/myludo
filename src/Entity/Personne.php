<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\EmptyController;
use App\Entity\Traits\Timestampable;
use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[UniqueEntity(
     fields:["nom"],
         message:"Le titre existe"
)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-personne'],
    ],
    denormalizationContext: [
        'groups' => ['write-personne'],
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
                         'summary' => "Replaces the Personne resource"
                  ]
                 ],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]
class Personne
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-personne','list-jouet'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le nom ne peut être null.")]
    #[Assert\NotBlank(message:"Le nom ne peut être vide.")]
    #[Assert\Length(
            min:2,
            max:255,
            minMessage:"Le nom doit avoir au moins 2 caractères.",
            maxMessage:"Le nom doit avoir au plus 255 caractères."
    )]
    #[Groups(['list-personne','list-jouet','write-personne'])]
    private ?string $nom = null;

    #[ORM\Column(type: Types::ARRAY)]
    #[Assert\NotNull(message:"Le rôle ne peut être null.")]
    #[Assert\NotBlank(message:"Le rôle ne peut être vide.")]
    #[Groups(['list-personne','list-jouet','write-personne'])]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['list-personne','write-personne'])]
    private ?string $facebook = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['list-personne','write-personne'])]
    private ?string $twitter = null;

    #[ORM\ManyToMany(targetEntity: Jouet::class, inversedBy: 'personnes')]
    private Collection $jouets;

     #[Assert\File(
          maxSize:"2M",
          mimeTypes:["image/*"],
          maxSizeMessage:"La taille de l'image doit être inférieur à 2M.",
          mimeTypesMessage:"Le fichier n'est pas un fichier image."
      )]
    #[Groups(['write-personne'])]
    public ?File $imageFile = null;

    #[ORM\OneToOne(inversedBy: 'personne', cascade: ['persist', 'remove'])]
    #[ApiProperty(readableLink: true, writableLink: false)]
    
    #[Groups(['list-personne'])]
    private ?Image $image = null;

    public function __construct()
    {
        $this->jouets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * @return Collection<int, Jouet>
     */
    public function getJouets(): Collection
    {
        return $this->jouets;
    }

    public function addJouet(Jouet $jouet): self
    {
        if (!$this->jouets->contains($jouet)) {
            $this->jouets->add($jouet);
        }

        return $this;
    }

    public function removeJouet(Jouet $jouet): self
    {
        $this->jouets->removeElement($jouet);

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
}
