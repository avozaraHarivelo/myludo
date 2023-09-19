<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\PretController;
use App\Entity\Traits\Timestampable;
use App\Repository\PretRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PretRepository::class)]

#[ApiResource(
    normalizationContext: [
        'groups' => ['list-pret'],
    ],
    denormalizationContext: [
        'groups' => ['write-pret'],
    ],
    collectionOperations: [
        'get' => ['security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')"],
        'post' => ['security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')"],
        'lend' => [
            'method' => 'POST',
            'path' => '/prets/admin',
            'read' => false,
            'controller' => PretController::class,
            'openapi_context' => [
                'summary' => 'lend Games in admin',                
                'description' => 'lend Games in admin',
                'requestBody' => [
                    'description' => 'lend data',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'userId' => [
                                        'type' => 'integer',
                                    ],
                                    'dateDebut' => [
                                        'type' => 'string',
                                    ],
                                    'dateFin' => [
                                        'type' => 'string',
                                    ],
                                    'gamesIdAdd' => [
                                        'type' => 'array',
                                        'example' => [1, 2, 3],
                                    ],
                                    'gamesIdAdd' => [
                                        'type' => 'array',
                                        'example' => [1, 2, 3],
                                    ],
                                ],
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '201' => [
                        'description' => 'lend succefully',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'status' => [
                                            'type' => 'string',
                                            'example' => 'Ok',
                                        ],
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
    ],
    itemOperations: [
        'get',
        'put'  => ['security' => "is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]
class Pret
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-pret','list-jouet'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message:"Le date de fin ne peut être null.")]
    #[Assert\NotBlank(message:"Le date de fin ne peut être vide.")]
    #[Groups(['list-pret','write-pret'])]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['list-pret','write-pret'])]
    private ?string $observation = null;

    #[ORM\ManyToOne(inversedBy: 'prets')]
    #[Groups(['list-pret','write-pret'])]
    #[ApiProperty( writableLink: false)]
    private ?User $user = null;

    

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message:"Le date de debut ne peut être null.")]
    #[Assert\NotBlank(message:"Le date de debut ne peut être vide.")]
    #[Groups(['list-pret','write-pret'])]
    private ?\DateTimeInterface $dateDebut = null;

  

    #[ORM\Column]
    #[Groups(['list-pret'])]
    private ?bool $retourner = null;

    #[ORM\ManyToOne(inversedBy: 'prets')]
    #[Groups(['list-pret'])]
    private ?Jouet $jouet = null;


    public function __construct()
    {
        $this->jouets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;

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

    

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * @return Collection<int, Jouet>
     */
    public function getJouets(): Collection
    {
        return $this->jouets;
    }

   
    public function isRetourner(): ?bool
    {
        return $this->retourner;
    }

    public function setRetourner(bool $retourner): self
    {
        $this->retourner = $retourner;

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
