<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\ListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ListeRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-li'],
    ],
    denormalizationContext: [
        'groups' => ['write-li'],
    ],
   
    collectionOperations: [
        'get'=> [
            'security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')",
        ],
        'post' => [
            'security' => "is_granted('ROLE_MEMBER')",
        ],
    ],
    itemOperations: [
        'get',
        'put' => ['security' => "is_granted('ROLE_MEMBER')"],
        'delete' => ['security' => "is_granted('ROLE_MEMBER')"],

    ]
)]
class Liste
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-li','list-jouet','list-user'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le nom  ne peut être null.")]
    #[Assert\NotBlank(message:"Le nom ne peut être null.")]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: "Le nom est au minimum 4 caractères",
        maxMessage: "Le nom est au maximum 100 caractères"
    )]
    #[Groups(['list-li','write-li'])]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'listes')]
    #[Groups(['write-li'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Jouet::class, inversedBy: 'listes')]
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
