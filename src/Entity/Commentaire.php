<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-commentaire'],
    ],
    denormalizationContext: [
        'groups' => ['write-commentaire'],
    ],

    collectionOperations: [
        'get',
        'post' => [
            'security' => "is_granted('ROLE_MEMBER')",
        ],
    ],
    itemOperations: [
        'get',
        'put' => ['security' => "is_granted('ROLE_MEMBER')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]
class Commentaire
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-commentaire','list-jouet'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['list-commentaire','list-jouet','write-commentaire'])]
    private ?string $contenue = null;

     /**
     * @ORM\Column(type="integer", options={"default": 0})
     */

    #[Assert\NotNull(message:"Le nombre de j'aime  ne peut être null.")]
    #[Assert\NotBlank(message:"Le nombre de j'aime  ne peut être null.")]
    #[Assert\PositiveOrZero(message:"Le nombre de j'aime  doit être égale  à 0 ou positive.")]
    #[Groups(['list-commentaire','list-jouet'])]
    private ?int $aimer = null;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */

    #[Assert\NotNull(message:"Le nombre de detestation  ne peut être null.")]
    #[Assert\NotBlank(message:"Le nombre de detestation  ne peut être null.")]
    #[Assert\PositiveOrZero(message:"Le nombre de detestation  doit être égale  à 0 ou positive.")]
    #[Groups(['list-commentaire','list-jouet'])]
    private ?int $detester = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires',)]
    #[Groups(['write-commentaire'])]
    private ?Jouet $jouet = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[Groups(['list-jouet'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(string $contenue): self
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getAimer(): ?int
    {
        return $this->aimer;
    }

    public function setAimer(int $aimer): self
    {
        $this->aimer = $aimer;

        return $this;
    }

    public function getDetester(): ?int
    {
        return $this->detester;
    }

    public function setDetester(int $detester): self
    {
        $this->detester = $detester;

        return $this;
    }

    public function getJouet(): ?Jouet
    {
        return $this->jouet;
    }

    public function setJouet(?Jouet $jouet): self
    {
        $this->jouet = $jouet;

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
