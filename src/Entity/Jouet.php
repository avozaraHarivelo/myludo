<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CollectionController;
use App\Controller\EmptyController;
use App\Controller\WishController;
use App\Entity\Traits\Timestampable;
use App\Repository\JouetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: JouetRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-jouet'],
    ],
    denormalizationContext: [
        'groups' => ['write-jouet'],
    ],

    collectionOperations: [
        'get',
        'post' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'input_formats' => ["multipart"=>"multipart/form-data"]
        ],
        
        'toWishList' => [
            'method' => 'POST',
            'path' => '/jouets/toWishList',
            'read' => false,
            'controller' => WishController::class,
            'openapi_context' => [
                'summary' => 'add Lessons to user wishList',                
                'description' => 'add Lessons to user wishList',
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'userId',
                        'schema' => [
                            'type' => 'integer'
                        ],
                        'description' => 'Client to add wishList'
                    ]
                ],
                'requestBody' => [
                    'description' => 'wish data',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'jouetId' => [
                                        'type' => 'integer',
                                    ],
                                    'jouetIdDelete' => [
                                        'type' => 'integer',
                                    ],
                                ],
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Jouet add to your wishList  succefully',
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
        'toCollectionList' => [
            'method' => 'POST',
            'path' => '/jouets/toCollectionList',
            'read' => false,
            'controller' => CollectionController::class,
            'openapi_context' => [
                'summary' => 'add Lessons to user collection',                
                'description' => 'add Lessons to user collection',
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'userId',
                        'schema' => [
                            'type' => 'integer'
                        ],
                        'description' => 'Client to add collection'
                    ]
                ],
                'requestBody' => [
                    'description' => 'collection data',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'jouetId' => [
                                        'type' => 'integer',
                                    ],
                                    'jouetIdDelete' => [
                                        'type' => 'integer',
                                    ],
                                ],
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Jouet add to your collection  succefully',
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
        'put' => [
            'security' => "is_granted('ROLE_ADMIN')",
                    'method' => "POST",
                    'controller' => EmptyController::class,
                    'input_formats' => [
                        'multipart' => ["multipart/form-data"],
                    ],
                  'openapi_context' => [
                         'summary' => "Replaces the Jouet resource"
                  ]
                 ],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]
class Jouet
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-jouet','list-tag','list-theme','list-mecanisme','list-pret','list-user'])]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le nom ne peut être null.")]
    #[Assert\NotBlank(message:"Le nom ne peut être vide.")]
    #[Assert\Length(
            min:4,
            max:255,
            minMessage:"Le nom doit avoir au moins 4 caractères.",
            maxMessage:"Le nom doit avoir au plus 255 caractères."
    )]
    #[Groups(['list-jouet','write-jouet','list-theme','list-mecanisme','list-pret'])]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['list-jouet','write-jouet'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['list-jouet','write-jouet'])]
    private ?string $contenue = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"Le(s) joueur(s) ne peut être null.")]
    #[Assert\NotBlank(message:"Le(s) joueur(s) ne peut être null.")]
    #[Assert\Positive(message:"Le nombre de joueur doit être positive.")]
    #[Groups(['list-jouet','write-jouet'])]
    private ?int $jouers = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"La description de l'age  ne peut être null.")]
    #[Assert\NotBlank(message:"La description de l'age  ne peut être null.")]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: "La longuer du description est au minimum 4 caractères",
        maxMessage: "La longuer du description est au maximum 50 caractères"
    )]
    #[Groups(['list-jouet','write-jouet'])]
    private ?string $age = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"L'année de sortie du jeux ne peut être null.")]
    #[Assert\NotBlank(message:"L'année de sortie du jeux  ne peut être null.")]
    #[Assert\Positive(message:"L'année de sortie du jeux doit être positive.")]
    #[Assert\Regex(pattern:'#^([0-9]{4})#',message:"L'année de sortie du jeux doit être en 4 chiffres.")]
    #[Groups(['list-jouet','write-jouet','list-pret'])]
    private ?int $annee = null;

    #[ORM\Column(type: Types::ARRAY)]
    #[Groups(['list-jouet','write-jouet'])]
    private array $cible = [];

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"La duration du temps de jeux  ne peut être null.")]
    #[Assert\NotBlank(message:"La duration du temps de jeux  ne peut être null.")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "La duration du temps de jeux est au minimum 4 caractères",
        maxMessage: "La duration du temps de jeux au maximum 50 caractères"
    )]
    #[Groups(['list-jouet','write-jouet'])]
    private ?string $duration = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['list-jouet','write-jouet'])]
    private array $langues = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['list-jouet','write-jouet'])]
    private ?string $codeBar = null;

    #[ORM\ManyToOne(inversedBy: 'jouets', cascade: ['persist', 'remove'])]
    #[Assert\NotNull(message:"Le catégorie  ne peut être null.")]
    #[Assert\NotBlank(message:"La duration du temps de jeux  ne peut être null.")]
    #[ORM\JoinColumn(onDelete:'SET NULL')]
    #[Groups(['list-jouet','write-jouet'])]
    private ?Categorie $categorie = null;

    #[ORM\ManyToMany(targetEntity: Theme::class, inversedBy: 'jouets')]
    #[Groups(['list-jouet'])]
    private Collection $themes;

    #[ORM\ManyToMany(targetEntity: Mecanisme::class, inversedBy: 'jouets')]
    #[Groups(['list-jouet','write-jouet'])]
    private Collection $mecanismes;



    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'collection')]
    private Collection $usersCollection;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'wishList')]
    private Collection $usersWish;

    #[ORM\ManyToMany(targetEntity: Liste::class, mappedBy: 'jouets')]
    #[Groups(['list-jouet'])]
    private Collection $listes;

    #[ORM\OneToMany(mappedBy: 'jouet', targetEntity: Note::class)]
    #[Groups(['list-jouet'])]
    private Collection $notes;

    #[ORM\OneToMany(mappedBy: 'jouet', targetEntity: Commentaire::class)]
    #[Groups(['list-jouet'])]
    private Collection $commentaires;

    #[ORM\ManyToMany(targetEntity: Personne::class, mappedBy: 'jouets')]
    #[Groups(['list-jouet','write-jouet'])]
    #[ApiProperty( writableLink: false)]
    private Collection $personnes;

    #[ORM\OneToMany(mappedBy: 'jouet', targetEntity: Video::class)]
    #[Groups(['list-jouet','write-jouet'])]
    private Collection $videos;

    #[ORM\ManyToMany(targetEntity: Recompense::class, inversedBy: 'jouets')]
    #[Groups(['list-jouet','write-jouet'])]
    private Collection $recompenses;

    #[ORM\Column]
    #[Groups(['list-jouet','write-jouet'])]
    private ?bool $isExtension = null;

    #[ORM\ManyToMany(targetEntity: self::class)]
    #[Groups(['list-jouet','write-jouet'])]
    private Collection $extensions;


    #[Assert\File(
        maxSize:"2M",
        mimeTypes:["image/*"],
        maxSizeMessage:"La taille de l'image doit être inférieur à 2M.",
        mimeTypesMessage:"Le fichier n'est pas un fichier image."
    )]
    #[Groups(['write-jouet'])]
    public ?File $imageFile = null;

    #[ORM\OneToOne(inversedBy: 'jouet', cascade: ['persist', 'remove'])]
    #[ApiProperty(readableLink: true, writableLink: false)]
    #[Groups(['list-jouet'])]
    private ?Image $image = null;

  

    #[ORM\Column(type:"boolean", options:["default"=> true])]
    // #[Assert\NotNull(message:"Le disponibilité ne peut pas être NULL.")]
    #[Groups(['list-jouet','list-pret'])]
    private ?bool $disponible = true;

    #[ORM\OneToMany(mappedBy: 'jouet', targetEntity: Pret::class)]
    #[Groups(['list-jouet'])]
    private Collection $prets;

    






    public function __construct()
    {
        $this->themes = new ArrayCollection();
        $this->mecanismes = new ArrayCollection();
        $this->usersCollection = new ArrayCollection();
        $this->usersWish = new ArrayCollection();
        $this->listes = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->personnes = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->recompenses = new ArrayCollection();
        $this->extensions = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->prets = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(?string $contenue): self
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getJouers(): ?int
    {
        return $this->jouers;
    }

    public function setJouers(int $jouers): self
    {
        $this->jouers = $jouers;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

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

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getCible(): array
    {
        return $this->cible;
    }

    public function setCible(array $cible): self
    {
        $this->cible = $cible;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLangues(): array
    {
        return $this->langues;
    }

    public function setLangues(?array $langues): self
    {
        $this->langues = $langues;

        return $this;
    }

    public function getCodeBar(): ?string
    {
        return $this->codeBar;
    }

    public function setCodeBar(?string $codeBar): self
    {
        $this->codeBar = $codeBar;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Theme>
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): self
    {
        if (!$this->themes->contains($theme)) {
            $this->themes->add($theme);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): self
    {
        $this->themes->removeElement($theme);

        return $this;
    }

    /**
     * @return Collection<int, Mecanisme>
     */
    public function getMecanismes(): Collection
    {
        return $this->mecanismes;
    }

    public function addMecanisme(Mecanisme $mecanisme): self
    {
        if (!$this->mecanismes->contains($mecanisme)) {
            $this->mecanismes->add($mecanisme);
        }

        return $this;
    }

    public function removeMecanisme(Mecanisme $mecanisme): self
    {
        $this->mecanismes->removeElement($mecanisme);

        return $this;
    }

    

    /**
     * @return Collection<int, User>
     */
    public function getUsersCollection(): Collection
    {
        return $this->usersCollection;
    }

    public function addUsersCollection(User $usersCollection): self
    {
        if (!$this->usersCollection->contains($usersCollection)) {
            $this->usersCollection->add($usersCollection);
            $usersCollection->addCollection($this);
        }

        return $this;
    }

    public function removeUsersCollection(User $usersCollection): self
    {
        if ($this->usersCollection->removeElement($usersCollection)) {
            $usersCollection->removeCollection($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersWish(): Collection
    {
        return $this->usersWish;
    }

    public function addUsersWish(User $usersWish): self
    {
        if (!$this->usersWish->contains($usersWish)) {
            $this->usersWish->add($usersWish);
            $usersWish->addWishList($this);
        }

        return $this;
    }

    public function removeUsersWish(User $usersWish): self
    {
        if ($this->usersWish->removeElement($usersWish)) {
            $usersWish->removeWishList($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Liste>
     */
    public function getListes(): Collection
    {
        return $this->listes;
    }

    public function addListe(Liste $liste): self
    {
        if (!$this->listes->contains($liste)) {
            $this->listes->add($liste);
            $liste->addJouet($this);
        }

        return $this;
    }

    public function removeListe(Liste $liste): self
    {
        if ($this->listes->removeElement($liste)) {
            $liste->removeJouet($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setJouet($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getJouet() === $this) {
                $note->setJouet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setJouet($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getJouet() === $this) {
                $commentaire->setJouet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Personne>
     */
    public function getPersonnes(): Collection
    {
        return $this->personnes;
    }

    public function addPersonne(Personne $personne): self
    {
        if (!$this->personnes->contains($personne)) {
            $this->personnes->add($personne);
            $personne->addJouet($this);
        }

        return $this;
    }

    public function removePersonne(Personne $personne): self
    {
        if ($this->personnes->removeElement($personne)) {
            $personne->removeJouet($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setJouet($this);
        }

        return $this;
    }

    public function removeVideo(video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getJouet() === $this) {
                $video->setJouet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recompense>
     */
    public function getRecompenses(): Collection
    {
        return $this->recompenses;
    }

    public function addRecompense(Recompense $recompense): self
    {
        if (!$this->recompenses->contains($recompense)) {
            $this->recompenses->add($recompense);
        }

        return $this;
    }

    public function removeRecompense(Recompense $recompense): self
    {
        $this->recompenses->removeElement($recompense);

        return $this;
    }

    public function isIsExtension(): ?bool
    {
        return $this->isExtension;
    }

    public function setIsExtension(bool $isExtension): self
    {
        $this->isExtension = $isExtension;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getExtensions(): Collection
    {
        return $this->extensions;
    }

    public function addExtension(self $extension): self
    {
        if (!$this->extensions->contains($extension)) {
            $this->extensions->add($extension);
        }

        return $this;
    }

    public function removeExtension(self $extension): self
    {
        $this->extensions->removeElement($extension);

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

   

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * @return Collection<int, Pret>
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets->add($pret);
            $pret->setJouet($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getJouet() === $this) {
                $pret->setJouet(null);
            }
        }

        return $this;
    }

    

}
