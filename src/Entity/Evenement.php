<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: EvenementRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-event'],
    ],
    denormalizationContext: [
        'groups' => ['write-event'],
    ],
    collectionOperations: [
        'get',
        'post' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ],
    ],
    itemOperations: [
        'put' => ['security' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]
class Evenement
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-event'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le titre d'un évenement  ne peut être null.")]
    #[Assert\NotBlank(message:"Le titre d'un évenement  ne peut être null.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le titre d'un évenement  est au minimum 4 caractères",
        maxMessage: "Le titre d'un évenement est au maximum 255 caractères"
    )]
    #[Groups(['list-event','write-event'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le lieu d'un évenement  ne peut être null.")]
    #[Assert\NotBlank(message:"Le lieu d'un évenement  ne peut être null.")]
    #[Groups(['list-event','write-event'])]
    private ?string $lieux = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['list-event','write-event'])]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message:"Le date fin d'un évenement  ne peut être null.")]
    #[Assert\NotBlank(message:"Le date fin d'un évenement  ne peut être null.")]
    #[Groups(['list-event','write-event'])]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?User $user = null;

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

    public function getLieux(): ?string
    {
        return $this->lieux;
    }

    public function setLieux(string $lieux): self
    {
        $this->lieux = $lieux;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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
}
