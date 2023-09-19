<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-tag'],
    ],
    denormalizationContext: [
        'groups' => ['write-tag'],
    ],
    collectionOperations: [
        'get',
        'post' => ['security' => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        'get',
        'put'=> ['security' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]
class Categorie
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-tag','list-jouet'])]
    
    private ?int $id = null;

    #[Assert\NotNull(message:"Le nom du catégorie ne peut être null.")]
    #[Assert\NotBlank(message:"Le nom du catégorie  ne peut être null.")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le nom du catégorie est au minimum 2 caractères",
        maxMessage: "Le nom du catégorie est au maximum 50 caractères"
    )]
    #[ORM\Column(length: 255)]
    #[Groups(['list-tag','write-tag','list-jouet'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Jouet::class)]
    #[Groups(['list-tag'])]
    private Collection $jouets;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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
            $jouet->setCategorie($this);
        }

        return $this;
    }

    public function removeJouet(Jouet $jouet): self
    {
        if ($this->jouets->removeElement($jouet)) {
            // set the owning side to null (unless already changed)
            if ($jouet->getCategorie() === $this) {
                $jouet->setCategorie(null);
            }
        }

        return $this;
    }
}
