<?php

namespace App\Tests\Functional;

use App\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    private function getEntity():User
    {
        return (new User('2qXTU5bt8AN9WJBlUoG0vYYiU5i2'))
            ->setEmail('john.doe@example.com')
            ->setLastName('Doe')
            ->setFirstName('John');
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $errors = $container->get('validator')->validate($this->getEntity());
        $this->assertCount(0, $errors);

    }

    public function testEmailIsValid(){
        self::bootKernel();
        $container = static::getContainer();
        $entity = $this->getEntity();
        $entity->setEmail('john.com');

        $errors = $container->get('validator')->validate($entity);
        $this->assertCount(1, $errors);
    }
}
