<?php

namespace Domain\Basket\Gateway;

use Domain\Authentication\Entity\User;
use Domain\Sales\Entity\Salable;

interface BasketStorageInterface
{
    public function setOwner(User $owner);

    public function getOwner(): ?User;

    public function addItem(Salable $item);
    public function getItem(string $uuid): ?Salable;
    public function removeItem(Salable $item);

    public function getItems(): array;
}
