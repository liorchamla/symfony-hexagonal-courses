<?php

namespace App\Tests\Domain\Authentication\Adapters;

use Domain\Authentication\Entity\User;
use Domain\Authentication\Gateway\UserRepositoryInterface;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * 
     *
     * @var User[] 
     */
    public array $users = [];

    public function findOneOrNull(string $uuid): ?User
    {
        return $this->users[$uuid] ?? null;
    }

    public function store(User $user)
    {
        $this->users[$user->uuid] = $user;
    }

    public function hasUserBoughtThisCourse(string $userUuid, string $courseUuid): bool
    {
        if (empty($this->users[$userUuid])) {
            return false;
        }

        $user = $this->users[$userUuid];

        foreach ($user->courses as $course) {
            if ($course->uuid === $courseUuid) {
                return true;
            }
        }

        return false;
    }
}
