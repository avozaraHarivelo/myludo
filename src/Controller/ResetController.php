<?php

namespace App\Controller;

use App\Repository\JouetRepository;
use App\Repository\PasswordTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;


#[AsController]
class ResetController extends AbstractController
{
    public function __construct(private MailerInterface $mailer, private UserRepository $userRepository, private PasswordTokenRepository $passwordTokenRepository)
    {
        $this->userRepository = $userRepository;

        $this->passwordTokenRepository = $passwordTokenRepository;
        $this->mailer = $mailer;
    }

    public function __invoke(Request $request): Response
    {
        $request_body = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneByEmail($request_body["email"]);

        $token = $this->passwordTokenRepository->findByUser($user)->getToken();


        $email = (new TemplatedEmail())
            ->from(new Address("noreply@appname.com", "App"))
            ->to($user->getEmail())
            ->subject("Réinitialisation de mot de passe")
            ->htmlTemplate("email.html.twig")
            ->context([
                'url' => $token
            ]);
        $this->mailer->send($email);

        return $this->json(array("status" => "ok"));
    }
}
