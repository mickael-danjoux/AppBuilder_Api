<?php

namespace App\Utils\Firebase;

use Kreait\Firebase\Factory;
use Symfony\Component\HttpKernel\KernelInterface;

class Firebase
{
    private Factory $factory;


    public function __construct(KernelInterface $kernel)
    {
        $this->factory = (new Factory())->withServiceAccount($kernel->getProjectDir() . '/' . $_SERVER['FIREBASE_CREDENTIALS']);
    }

    /**
     * @return Factory
     */
    public function getFactory(): Factory
    {
        return $this->factory;
    }
}
