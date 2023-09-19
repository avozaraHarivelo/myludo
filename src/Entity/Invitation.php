<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: InvitationRepository::class)]
#[ApiResource()]
class Invitation
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le contenue  ne peut être null.")]
    #[Assert\NotBlank(message:"Le contenue ne peut être null.")]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: "Le contenue est au minimum 4 caractères",
        maxMessage: "Le contenue est au maximum 150 caractères"
    )]
    private ?string $contenue = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
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
