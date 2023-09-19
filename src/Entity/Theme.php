<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ThemeRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-theme'],
    ],
    denormalizationContext: [
        'groups' => ['write-theme'],
    ],

    collectionOperations: [
        'get',
        'post'=> ['security' => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        'get',
        'put'=> ['security' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]
class Theme
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-jouet','list-theme'])]
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
    #[Groups(['list-jouet','list-theme','write-theme'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Jouet::class, mappedBy: 'themes')]
    #[Groups(['list-theme'])]
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
            $jouet->addTheme($this);
        }

        return $this;
    }

    public function removeJouet(Jouet $jouet): self
    {
        if ($this->jouets->removeElement($jouet)) {
            $jouet->removeTheme($this);
        }

        return $this;
    }
}
