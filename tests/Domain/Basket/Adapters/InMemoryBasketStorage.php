<?php

namespace App\Tests\Domain\Basket\Adapters;

use Domain\Authentication\Entity\User;
use Domain\Basket\Gateway\BasketStorageInterface;
use Domain\Sales\Entity\Salable;

class InMemoryBasketStorage implements BasketStorageInterface
{

    /** @var Salable[] */
    public array $items = [];
    public ?User $owner;

    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function addItem(Salable $item)
    {
        $this->items[$item->getUuid()] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItem(string $uuid): ?Salable
    {
        return $this->items[$uuid] ?? null;
    }

    public function hasItem(Salable $item): bool
    {
        return array_key_exists($item->getUuid(), $this->items);
    }

    public function removeItem(Salable $item)
    {
        if ($this->hasItem($item)) {
            unset($this->items[$item->getUuid()]);
        }
    }
}
