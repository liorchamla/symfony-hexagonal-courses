<?php

namespace Domain\Sales\Entity;

use Ramsey\Uuid\Uuid;
use Domain\Basket\BasketManager;
use Domain\Authentication\Entity\User;

class Sale
{
    public array $items = [];
    public User $buyer;
    public string $uuid;

    public function __construct(?User $buyer = null, array $items = [])
    {
        $this->uuid = Uuid::uuid1();
        $this->buyer = $buyer;
        $this->items = $items;
    }

    public function getTotalAmount(): int
    {
        return array_reduce($this->items, fn (int $total, Salable $item) => $total + $item->getPrice(), 0);
    }

    public static function createFromBasket(BasketManager $basketManager): self
    {
        return  new self($basketManager->getOwner(), $basketManager->getItems());
    }
}
