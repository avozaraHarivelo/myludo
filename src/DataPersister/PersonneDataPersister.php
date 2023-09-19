<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Image;
use App\Entity\Personne;
use Symfony\Component\String\Slugger\SluggerInterface;

class PersonneDataPersister implements DataPersisterInterface
{
    public function __construct(private DataPersisterInterface $decorated,private SluggerInterface $slugger)
    {
    }

    public function supports($data): bool
    {
        return $data instanceof Personne;
    }

    public function persist($data): void
    {

        if ($data->imageFile) {
            if ($data->getImage()) {

                $image = $data->getImage()
                    ->setFile($data->imageFile)
                    ->setFilePath(null)
                    ->setSlug($this->slugger->slug(strtolower($data->getNom()))."_".bin2hex(random_bytes(5)));
            } else {

                $image = (new Image())
                    ->setFile($data->imageFile)
                    ->setPersonne($data)
                    ->setSlug($this->slugger->slug(strtolower($data->getNom()))."_".bin2hex(random_bytes(5)));
            }
            $this->decorated->persist($image);
        } else {

            if ($data->getImage() == null) {
                $image =  (new Image())
                    ->setFilePath("default.jpg")
                    ->setPersonne($data)
                    ->setSlug($this->slugger->slug(strtolower($data->getWording()))."_".bin2hex(random_bytes(5)));

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
