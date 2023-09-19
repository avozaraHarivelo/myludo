<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Theme;
use Symfony\Component\String\Slugger\SluggerInterface;

class ThemeDataPersister implements DataPersisterInterface
{
    public function __construct(private DataPersisterInterface $decorated,private SluggerInterface $slugger)
    {
    }

    public function supports($data): bool
    {
        return $data instanceof Theme;
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
