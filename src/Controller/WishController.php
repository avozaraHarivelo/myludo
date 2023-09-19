<?php

namespace App\Controller;

use App\Repository\JouetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;


#[AsController]
class WishController extends AbstractController
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

        if ($this->security->isGranted('ROLE_MEMBER') || $this->security->isGranted('ROLE_ADMIN')) {
            $request_body = json_decode($request->getContent(), true);
            $client = $this->userRepository->findOneById($request->get('userId'));
           


            if ($this->security->isGranted('ROLE_MEMBER') || $this->security->isGranted('ROLE_ADMIN')) {
                if (array_key_exists('jouetIdDelete', $request_body) && $request_body["jouetIdDelete"]!= 0) {
                    $jouet = $this->jouetRepository->findOneById($request_body["jouetIdDelete"]);
                   
                    $client->removeWishList($jouet);
                    $this->em->persist($client);
                    $this->em->flush();
                }
            }



            if (array_key_exists('jouetId', $request_body) && $request_body["jouetId"] != 0) {
                $jouet = $this->jouetRepository->findOneById($request_body["jouetId"]);
                    $client->addWishList($jouet);
                    $this->em->persist($client);
                    $this->em->flush();
            }

           


            $response = array("status" => "Ok");
        } else $response = array("status" => "Il faut être un client ou admin");

        return $this->json($response);
    }
}
