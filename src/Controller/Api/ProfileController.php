<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class ProfileController extends AbstractController
{
    #[Route(path: '/profile')]
    public function __invoke(Security $security): Response
    {
        return $this->json($security);
    }
}
