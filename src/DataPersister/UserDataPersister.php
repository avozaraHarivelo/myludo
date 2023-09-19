<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private UserPasswordHasherInterface $hasher,
        private Security $security,
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = []): void
    {
        if (isset($context["collection_operation_name"])) {
            if (!$data->getPlainPassword()) throw new BadRequestException("Mot de passe non fourni");
            try {
               
                if ($data->getUsername() === null || $data->getUsername() === "") {
                    $data->setUsername($data->getNom()."_". $data->getPrenom());
                }
                $data->setPassword($this->hasher->hashPassword($data, $data->getPlainPassword()));

                if ($this->security->isGranted('ROLE_ADMIN')) {

              
                    $data->setRoles(['ROLE_USER','ROLE_ADMIN']);
                    $data->setBloquer(false);


                    // $email = (new TemplatedEmail())
                    //     ->from(new Address("noreply@appname.com", "App"))
                    //     ->to($data->getEmail())
                    //     ->subject("Nouveau compte")
                    //     ->htmlTemplate("email.html.twig")
                    //     ->context([
                    //         'password' => $data->getPlainPassword()
                    //     ]);
                    // $this->mailer->send($email);
                }else{
                    $data->setBloquer(true);

                    $data->setRoles(['ROLE_USER','ROLE_MEMBER']);
                }
            } catch (\Throwable) {
            }
        } else if ($data->getPlainPassword())
            $data->setPassword($this->hasher->hashPassword($data, $data->getPlainPassword()));
        $this->decorated->persist($data);
    }

    public function remove($data, array $context = [])
    {
        if ($data !== $this->security->getUser())
            $this->decorated->remove($data);
    }
}
