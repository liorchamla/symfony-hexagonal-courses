<?php

namespace App\Tests\Application\Courses\Adapter;

use Symfony\Component\Security\Core\User\UserInterface;

class MockUserInterface implements UserInterface
{
    public string $uuid;

    public function __construct(string $uuid = 'test-user-uuid')
    {
        $this->uuid = $uuid;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
    }
    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
    }
    public function getSalt()
    {
    }
}
