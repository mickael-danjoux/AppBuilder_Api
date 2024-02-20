<?php

namespace App\Tests\Functional;

use App\ApiResources\Registration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RegistrationTest extends KernelTestCase
{
    public function getRegistrationDto() : Registration
    {
        return (new Registration())
            ->setEmail('johndoe-sware@mailsac.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setPassword('password');
    }

    public function testSomething(): void
    {
        self::bootKernel();
        $container = static::getContainer();

    }
}
