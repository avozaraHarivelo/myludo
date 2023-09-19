<?php

namespace App\EventSubscriber;

use App\Repository\PasswordTokenRepository;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ForgotEventSubscriber  implements EventSubscriberInterface

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
            || !preg_match('/^coop_tilleuls_forgot_password.update/i', $event->getRequest()->get('_route'))
        ) {

            return;
        }
        // dd($event->getRequest()->get("password"));

        
        $token =  $event->getRequest()->get("tokenValue");
        $password =  $event->getRequest()->get("password");

        $id = $this->passwordTokenRepository->findOneByToken($token)->getUser()->getId();
        $user = $this->userRepository->findOneById($id);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $this->em->persist($user);
        $this->em->flush();
    }
}
