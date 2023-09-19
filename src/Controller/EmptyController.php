<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Attribute\AsController;

class EmptyController
{
    public function __invoke($data)
    {
        return $data;
    }
}