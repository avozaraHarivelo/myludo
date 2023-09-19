<?php

namespace App\Controller;

use App\Entity\Pret;
use App\Repository\JouetRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;


#[AsController]
class PretController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private UserRepository $userRepository,
        private JouetRepository $jouetRepository,
    ) {
        $this->em = $em;
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->jouetRepository = $jouetRepository;
    }

    public function __invoke(Request $request): Response
    {

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $request_body = json_decode($request->getContent(), true);
            $client = $this->userRepository->findOneById($request_body['userId']);



            if (array_key_exists('gamesIdAdd', $request_body) && $request_body["gamesIdAdd"]) {



                foreach ($this->jouetRepository->listAllJouetsByIds($request_body['gamesIdAdd']) as $key => $jouet) {
                    $dateDebut = explode('/', $request_body["dateDebut"]);
                    $dateFin = explode('/', $request_body["dateFin"]);
                    $this->em->persist((new Pret())
                        ->setUser($client)
                        ->setJouet($jouet)
                        ->setDateDebut(new DateTimeImmutable($dateDebut[2]."-".$dateDebut[1]."-".$dateDebut[0]))
                        ->setDateFin(new DateTimeImmutable($dateFin[2]."-".$dateFin[1]."-".$dateFin[0]))
                        ->setRetourner(false));

                }

                $this->em->flush();
            }




            $response = array("status" => "Ok");
        } else $response = array("status" => "Il faut être un  admin");

        return $this->json($response);
    }
}
