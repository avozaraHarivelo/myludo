<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: NoteRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-note'],
    ],
    denormalizationContext: [
        'groups' => ['write-note'],
    ],
    collectionOperations: [
        'post' => ['security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')"],
    ],
    itemOperations: [
        'get',
        'put' => ['security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')"],

    ]
)]
class Note
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-jouet','list-user'])]
    private ?int $id = null;

    
    #[ORM\Column(type:"float",scale:2)]
    #[Assert\NotNull(message:"Le nombre de detestation  ne peut être null.")]
    #[Assert\NotBlank(message:"Le nombre de detestation  ne peut être null.")]
    #[Groups(['write-note', 'list-jouet','list-user'])]
    private ?float $note = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[Groups(['write-note'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[Groups(['write-note'])]
    private ?Jouet $jouet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

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

    public function getJouet(): ?Jouet
    {
        return $this->jouet;
    }

    public function setJouet(?Jouet $jouet): self
    {
        $this->jouet = $jouet;

        return $this;
    }
}
