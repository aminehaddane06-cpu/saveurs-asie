<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    // Déplace le cache dans la mémoire interne du conteneur Linux (élimine le goulot d'étranglement Windows)
    public function getCacheDir(): string
    {
        return '/tmp/symfony/cache/' . $this->environment;
    }

    // Déplace les logs dans le conteneur Linux
    public function getLogDir(): string
    {
        return '/tmp/symfony/log';
    }
}