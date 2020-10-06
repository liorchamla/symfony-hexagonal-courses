<?php

namespace Domain\Authentication\Gateway;

use Domain\Authentication\Entity\User;

interface UserRepositoryInterface
{

    public function findOneOrNull(string $uuid): ?User;

    public function store(User $user);

    public function hasUserBoughtThisCourse(string $userUuid, string $courseUuid);
}
