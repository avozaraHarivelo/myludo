<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-video'],
    ],
    denormalizationContext: [
        'groups' => ['write-video'],
    ],
    operations:[
        new GetCollection(),
        new Post(security:"is_granted('ROLE_ADMIN')"),
        new Delete(security:"is_granted('ROLE_ADMIN')")

    ],
)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-jouet','list-video'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le lien ne peut être vide.")]
    #[Assert\NotNull(message:"Le lien ne peut être NULL.")]
    #[Groups(['list-jouet','write-video','list-video'])]
    private ?string $lien = null;

    #[ORM\ManyToOne(inversedBy: 'videos')]
    #[Groups(['write-video'])]
    private ?Jouet $jouet = null;

    #[ORM\ManyToOne(inversedBy: 'videos')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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
