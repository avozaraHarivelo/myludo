<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\Timestampable;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\EmptyController;
use App\Controller\ResetController;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-user'],
    ],
    denormalizationContext: [
        'groups' => ['write-user'],
    ],
    collectionOperations: [
        'get',
        'post' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'validation_groups' => ["Default", "post-user"],
        ],
        'sendMailForgot' => [
            'method' => 'POST',
            'path' => '/users/mailReset',
            'read' => false,
            'controller' => ResetController::class,
            'openapi_context' => [
                'summary' => 'send mail to reset password',                
                'description' => 'send mail to reset password',
                'requestBody' => [
                    'description' => 'email',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => [
                                        'type' => 'string',
                                    ],
                                ],
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Mail send  succefully',
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
        'register' => [
            'method' => 'POST',
            'path' => '/register',
            'controller' => EmptyController::class,
            'openapi_context' => [
                "summary" => "Register a new User"
            ],
        ],
    ],
    itemOperations: [
        'get'=> ['security' => "is_granted('ROLE_ADMIN') or object == user"],
        'put' => ['security' => "is_granted('ROLE_ADMIN') or object == user"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-user','list-pret','list-jouet'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message:"L'email ne peut être vide.")]
    #[Assert\NotNull(message:"L'email ne peut être NULL.")]
    #[Assert\Email(message:"L'email n'est pas valide.")]
    #[Assert\Length(
        max:180,
         maxMessage:"Adresse email trop longue, doit avoir au plus 180 caractères."
     )]
     #[Groups(['list-user','write-user'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['list-user'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['list-user','write-user','list-pret','list-jouet','list-jouet'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['list-user','write-user','list-pret','list-jouet'])]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list-user'])]
    private ?string $username = null;

     /**
     * @ORM\Column(type="boolean",options={"default": true})
     */
    #[Groups(['list-user'])]
    private ?bool $visbleCollection = null;



     /**
     * @ORM\Column(type="boolean",options={"default": true})
     */
    #[Groups(['list-user'])]
    private ?bool $visibleWishlist = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Le numéro de téléphne ne peut être vide.")]
    #[Assert\NotNull(message:"Le numéro de téléphne ne peut être NULL.")]
    #[Assert\Length(
          min:11,
          max:25,
          minMessage:"Le numéro téléphone doit avoir au moins 11 caractères.",
         maxMessage:"Le numéro téléphone doit avoir au plus 25 caractères."
      )]
      #[Assert\Regex(
           pattern : "/^\+[1-9]\d{0,2}(\s|\d|\s|\(|\)){9,}$/",
           message :"Numéro téléphone invalide"
       )]
       #[Groups(['list-user','write-user'])]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Pret::class)]
    private Collection $prets;

    #[ORM\ManyToMany(targetEntity: Jouet::class, inversedBy: 'usersCollection')]
    #[JoinTable(name: "jouets_collection")]
    #[Groups(['list-user'])]
    private Collection $collection;

    #[ORM\ManyToMany(targetEntity: Jouet::class, inversedBy: 'usersWish')]
    #[JoinTable(name: "jouets_wish")]
    #[Groups(['list-user'])]
    private Collection $wishList;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Liste::class)]
    #[Groups(['list-user'])]
    private Collection $listes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Note::class)]
    #[Groups(['list-user'])]
    private Collection $notes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Invitation::class)]
    private Collection $invitations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Video::class)]
    private Collection $videos;

    #[ORM\ManyToMany(targetEntity: self::class)]
    private Collection $Amies;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Blog::class)]
    private Collection $blogs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Evenement::class)]
    private Collection $evenements;

    
    #[Assert\NotNull(
        groups:["post-user"],
        message:"Le mot de passe est obligatoire.")]
    #[Assert\Length(
        min:4,
        max:25,
        minMessage:"Le mot de passe doit avoir au moins 4 caractères.",
        maxMessage:"Le mot de passe doit avoir au plus 25 caractères."
    )]
    #[Groups(["write-user"])]
    private ?string $plainPassword = null;

    #[ORM\Column]
    #[Groups(['list-user','write-user'])]
    private ?bool $bloquer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAbonnement = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Abonnement $abonnement = null;

  

    public function __construct()
    {
        $this->prets = new ArrayCollection();
        $this->collection = new ArrayCollection();
        $this->wishList = new ArrayCollection();
        $this->listes = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->amies = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->Amies = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function isVisbleCollection(): ?bool
    {
        return $this->visbleCollection;
    }

    public function setVisbleCollection(bool $visbleCollection): self
    {
        $this->visbleCollection = $visbleCollection;

        return $this;
    }

    public function isVisibleWishlist(): ?bool
    {
        return $this->visibleWishlist;
    }

    public function setVisibleWishlist(bool $visibleWishlist): self
    {
        $this->visibleWishlist = $visibleWishlist;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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
            $pret->setUser($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getUser() === $this) {
                $pret->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Jouet>
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    public function addCollection(Jouet $collection): self
    {
        if (!$this->collection->contains($collection)) {
            $this->collection->add($collection);
        }

        return $this;
    }

    public function removeCollection(Jouet $collection): self
    {
        $this->collection->removeElement($collection);

        return $this;
    }

    /**
     * @return Collection<int, Jouet>
     */
    public function getWishList(): Collection
    {
        return $this->wishList;
    }

    public function addWishList(Jouet $wishList): self
    {
        if (!$this->wishList->contains($wishList)) {
            $this->wishList->add($wishList);
        }

        return $this;
    }

    public function removeWishList(Jouet $wishList): self
    {
        $this->wishList->removeElement($wishList);

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
            $liste->setUser($this);
        }

        return $this;
    }

    public function removeListe(Liste $liste): self
    {
        if ($this->listes->removeElement($liste)) {
            // set the owning side to null (unless already changed)
            if ($liste->getUser() === $this) {
                $liste->setUser(null);
            }
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
            $note->setUser($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getUser() === $this) {
                $note->setUser(null);
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
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setUser($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getUser() === $this) {
                $invitation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setUser($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getUser() === $this) {
                $video->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAmies(): Collection
    {
        return $this->Amies;
    }

    public function addAmy(self $amy): self
    {
        if (!$this->Amies->contains($amy)) {
            $this->Amies->add($amy);
        }

        return $this;
    }

    public function removeAmy(self $amy): self
    {
        $this->Amies->removeElement($amy);

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setUser($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getUser() === $this) {
                $blog->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setUser($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getUser() === $this) {
                $evenement->setUser(null);
            }
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function isBloquer(): ?bool
    {
        return $this->bloquer;
    }

    public function setBloquer(bool $bloquer): self
    {
        $this->bloquer = $bloquer;

        return $this;
    }

    public function getDateAbonnement(): ?\DateTimeInterface
    {
        return $this->dateAbonnement;
    }

    public function setDateAbonnement(?\DateTimeInterface $dateAbonnement): self
    {
        $this->dateAbonnement = $dateAbonnement;

        return $this;
    }

    public function getAbonnement(): ?Abonnement
    {
        return $this->abonnement;
    }

    public function setAbonnement(?Abonnement $abonnement): self
    {
        $this->abonnement = $abonnement;

        return $this;
    }

   

   
}
