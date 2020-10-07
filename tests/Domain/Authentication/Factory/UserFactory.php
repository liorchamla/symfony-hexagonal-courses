<?php

namespace App\Tests\Domain\Authentication\Factory;

use Domain\Authentication\Entity\User;

class UserFactory
{
    public static function create(array $data = []): User
    {
        $data = array_merge(
            [
            'courses' => [],
            ], $data
        );

        return new User($data['courses']);
    }
}
