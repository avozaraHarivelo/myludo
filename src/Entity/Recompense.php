<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\EmptyController;
use App\Entity\Traits\Timestampable;
use App\Repository\RecompenseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RecompenseRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-recompense'],
    ],
    denormalizationContext: [
        'groups' => ['write-recompense'],
    ],
    collectionOperations: [
        'get'=>['security' => "is_granted('ROLE_USER')"],
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
                         'summary' => "Replaces the Recompense resource"
                  ]
                 ],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]
class Recompense
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-recompense','write-recompense','list-jouet'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le nom ne peut être null.")]
    #[Assert\NotBlank(message:"Le nom ne peut être null.")]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "Le nom est au minimum 4 caractères",
        maxMessage: "Le nom est au maximum 100 caractères"
    )]
    #[Groups(['list-recompense','write-recompense','list-jouet'])]
    private ?string $nom = null;

    #[ORM\ManyToMany(targetEntity: Jouet::class, mappedBy: 'recompenses')]
    private Collection $jouets;

    #[Assert\File(
        maxSize:"2M",
        mimeTypes:["image/*"],
        maxSizeMessage:"La taille de l'image doit être inférieur à 2M.",
        mimeTypesMessage:"Le fichier n'est pas un fichier image."
    )]
  #[Groups(['write-recompense'])]
  public ?File $imageFile = null;

    #[ORM\OneToOne(inversedBy: 'recompense', cascade: ['persist', 'remove'])]
    #[ApiProperty(readableLink: true, writableLink: false)]
    #[Groups(['list-recompense'])]
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
            $jouet->addRecompense($this);
        }

        return $this;
    }

    public function removeJouet(Jouet $jouet): self
    {
        if ($this->jouets->removeElement($jouet)) {
            $jouet->removeRecompense($this);
        }

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
