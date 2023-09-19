<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Pret;
use App\Exception\JouetsNotDisponibleException;
use Symfony\Component\String\Slugger\SluggerInterface;

class PretDataPersister implements DataPersisterInterface
{
    public function __construct(private DataPersisterInterface $decorated, private SluggerInterface $slugger)
    {
    }

    public function supports($data): bool
    {
        return $data instanceof Pret;
    }

    public function persist($data, array $context = []): void
    {

        if (isset($context["operation_name"]) && $context["operation_name"]==="api_prets_put_item") {
           
            foreach ($data->getJouets() as $jouet) {
                $jouet->setDisponible(true);
                $this->decorated->persist($jouet);
            }
            $data->setRetourner(true);
            $this->decorated->persist($data);
        } else {
            $dispo = true;
            foreach ($data->getJouets() as $jouet) {
                if (!$jouet->isDisponible())  $dispo = false;
            }
            if ($dispo) {
                foreach ($data->getJouets() as $jouet) {
                    $jouet->setDisponible(false);
                    $this->decorated->persist($jouet);
                }

                $data->setRetourner(false);
                $this->decorated->persist($data);
            } else {
                throw new JouetsNotDisponibleException('notDisponible');
            }
        }
    }

    public function remove($data)
    {
        $this->decorated->remove($data);
    }
}
