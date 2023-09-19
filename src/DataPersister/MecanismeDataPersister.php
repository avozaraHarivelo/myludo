<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Mecanisme;
use Symfony\Component\String\Slugger\SluggerInterface;

class MecanismeDataPersister implements DataPersisterInterface
{
    public function __construct(private DataPersisterInterface $decorated,private SluggerInterface $slugger)
    {
    }

    public function supports($data): bool
    {
        return $data instanceof Mecanisme;
    }

    public function persist($data): void
    {
      
        $data->setSlug($this->slugger->slug(strtolower($data->getNom())));
        $this->decorated->persist($data);
    }

    public function remove($data)
    {
        $this->decorated->remove($data);
    }
}
