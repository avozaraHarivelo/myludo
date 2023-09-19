<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Categorie;
use App\Entity\Image;
use App\Entity\Jouet;
use Symfony\Component\String\Slugger\SluggerInterface;

class JouetDataPersister implements DataPersisterInterface
{
    public function __construct(private DataPersisterInterface $decorated,private SluggerInterface $slugger)
    {
    }

    public function supports($data): bool
    {
        return $data instanceof Jouet;
    }

    public function persist($data): void
    {
        $data->setSlug($this->slugger->slug(strtolower($data->getNom()))."_".bin2hex(random_bytes(5)));
      
        if ($data->imageFile) {
            if ($data->getImage()) {

                $image = $data->getImage()
                    ->setFile($data->imageFile)
                    ->setFilePath(null)
                    ->setSlug($this->slugger->slug(strtolower($data->getNom()))."_".bin2hex(random_bytes(5)));
            } else {

                $image = (new Image())
                    ->setFile($data->imageFile)
                    ->setJouet($data)
                    ->setSlug($this->slugger->slug(strtolower($data->getNom()))."_".bin2hex(random_bytes(5)));
            }
            $this->decorated->persist($image);
        } else {

            if ($data->getImage() == null) {
                $image =  (new Image())
                    ->setFilePath("default.jpg")
                    ->setJouet($data)
                    ->setSlug($this->slugger->slug(strtolower($data->getNom()))."_".bin2hex(random_bytes(5)));

                $this->decorated->persist($image);
            }
        }

       
      
        $this->decorated->persist($data);
    }

    public function remove($data)
    {
        $this->decorated->remove($data);
    }
}
