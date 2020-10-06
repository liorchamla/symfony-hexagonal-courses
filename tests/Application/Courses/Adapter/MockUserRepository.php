<?php

namespace App\Tests\Application\Courses\Adapter;

use Domain\Authentication\Entity\User;
use Domain\Authentication\Gateway\UserRepositoryInterface;

class MockUserRepository implements UserRepositoryInterface
{

    public bool $isAuthenticated;
    public bool $hasBoughtCourse;

    public function __construct(bool $isAuthenticated = false, bool $hasBoughtCourse = false)
    {
        $this->isAuthenticated = $isAuthenticated;
        $this->hasBoughtCourse = $hasBoughtCourse;
    }

    public function findOneOrNull(string $uuid): ?User
    {
        return $this->isAuthenticated ? new User([]) : null;
    }

    public function store(User $user)
    {
        //
    }

    public function hasUserBoughtThisCourse(string $userUuid, string $courseUuid)
    {
        return $this->hasBoughtCourse;
    }
}
