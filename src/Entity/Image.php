<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
/**
 * @Vich\Uploadable
 */
#[ApiResource(
    normalizationContext: [
        'groups' => ['list-image'],
    ],
    denormalizationContext: [
        'groups' => ['write-image'],
    ],
    itemOperations: [
        'get',
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],

    ]
)]

class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list-blog','list-jouet','list-recompense','list-personne','list-image'])]
    private ?int $id = null;
    

     /**
     * @Vich\UploadableField(mapping="image", fileNameProperty="filePath")
     */
    #[Assert\NotNull()]
    #[Groups(['write-image'])]
    public ?File $file = null;

    #[ORM\Column(length: 255)]
    #[Groups(['write-image'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filePath  = null;

    #[Groups(['list-blog','list-jouet','list-recompense','list-personne','list-image'])]
    #[ApiProperty(types: ['https://schema.org/imageUrl'])]
    public ?string $imageUrl = null;


    #[ORM\OneToOne(mappedBy: 'image', cascade: ['persist', 'remove'])]
    private ?Personne $personne = null;

    #[ORM\OneToOne(mappedBy: 'image', cascade: ['persist', 'remove'])]
    private ?Blog $blog = null;

    #[ORM\OneToOne(mappedBy: 'image', cascade: ['persist', 'remove'])]
    private ?Recompense $recompense = null;

    #[ORM\OneToOne(mappedBy: 'image', cascade: ['persist', 'remove'])]
    private ?Jouet $jouet = null;


    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): Image
    {
        $this->file = $file;

        return $this;
    }

   

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): self
    {
        // unset the owning side of the relation if necessary
        if ($personne === null && $this->personne !== null) {
            $this->personne->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($personne !== null && $personne->getImage() !== $this) {
            $personne->setImage($this);
        }

        $this->personne = $personne;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        // unset the owning side of the relation if necessary
        if ($blog === null && $this->blog !== null) {
            $this->blog->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($blog !== null && $blog->getImage() !== $this) {
            $blog->setImage($this);
        }

        $this->blog = $blog;

        return $this;
    }

    public function getRecompense(): ?Recompense
    {
        return $this->recompense;
    }

    public function setRecompense(?Recompense $recompense): self
    {
        // unset the owning side of the relation if necessary
        if ($recompense === null && $this->recompense !== null) {
            $this->recompense->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($recompense !== null && $recompense->getImage() !== $this) {
            $recompense->setImage($this);
        }

        $this->recompense = $recompense;

        return $this;
    }

    public function getJouet(): ?Jouet
    {
        return $this->jouet;
    }

    public function setJouet(?Jouet $jouet): self
    {
        // unset the owning side of the relation if necessary
        if ($jouet === null && $this->jouet !== null) {
            $this->jouet->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($jouet !== null && $jouet->getImage() !== $this) {
            $jouet->setImage($this);
        }

        $this->jouet = $jouet;

        return $this;
    }
}
