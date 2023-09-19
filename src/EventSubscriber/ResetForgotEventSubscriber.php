<?php

namespace App\EventSubscriber;

use App\Exception\UserNotFoundException;
use App\Repository\PasswordTokenRepository;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResetForgotEventSubscriber  implements EventSubscriberInterface

{
    private $em;
    private $passwordTokenRepository;
    private $userRepository;

    public function __construct(
        private EntityManagerInterface $manager,
        private PasswordTokenRepository $PasswordTokenRepository,
        private UserRepository $UserRepository,
        private UserPasswordHasherInterface $hasher,
    ) {

        $this->passwordTokenRepository = $PasswordTokenRepository;
        $this->userRepository = $UserRepository;
        $this->em = $manager;
    }

    public static function getSubscribedEvents()
    {
        return [
            // Symfony 4.3 and inferior, use 'kernel.request' event name
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {


        if (
            !$event->isMainRequest()
            || !preg_match('/^coop_tilleuls_forgot_password.reset/i', $event->getRequest()->get('_route'))
        ) {

            return;
        }
        if(!$this->userRepository->findOneByEmail($event->getRequest()->get("value")))throw new UserNotFoundException('utilisateur introvable');
    }
}
