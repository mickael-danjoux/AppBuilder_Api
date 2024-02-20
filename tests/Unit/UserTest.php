<?php

namespace App\Tests\Unit;

use App\Entity\User\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private function getEntity():User
    {
        return (new User('2qXTU5bt8AN9WJBlUoG0vYYiU5i2'))
            ->setEmail('john.doe@example.com')
            ->setLastName('doe')
            ->setFirstName('john');
    }

    public function testIsFormattedName(): void
    {
        $user = $this->getEntity();
        $this->assertSame('John DOE', $user->getDisplayName());
    }

    public function testInverseName(): void
    {
        $user = $this->getEntity();
        $this->assertSame('DOE John', $user->getDisplayName(true));
    }
}
