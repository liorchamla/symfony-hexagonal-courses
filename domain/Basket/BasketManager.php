<?php

namespace Domain\Basket;

use Domain\Sales\Entity\Salable;
use Domain\Basket\Entity\Discount;
use Domain\Authentication\Entity\User;
use Domain\Basket\Discount\DiscountComputation;
use Domain\Basket\Gateway\BasketStorageInterface;
use Domain\Basket\Gateway\DiscountStorageInterface;

class BasketManager
{

    protected BasketStorageInterface $storage;
    protected DiscountStorageInterface $discountStorage;

    protected DiscountComputation $discountComputation;

    public function __construct(BasketStorageInterface $storage, DiscountComputation $discountComputation, DiscountStorageInterface $discountStorage)
    {
        $this->storage = $storage;
        $this->discountComputation = $discountComputation;
        $this->discountStorage = $discountStorage;
    }


    public function setOwner(User $owner): self
    {
        $this->storage->setOwner($owner);
        return $this;
    }

    public function getOwner(): User
    {
        return $this->storage->getOwner();
    }


    public function applyDiscount(Discount $discount): self
    {
        $this->discountStorage->addDiscount($discount);
        return $this;
    }

    public function getAppliedDiscounts(): array
    {
        return $this->discountStorage->getDiscounts();
    }

    public function getTotalAmountWithDiscounts(): int
    {
        return $this->discountComputation->getComputedTotalAmount(
            $this->getItems(),
            $this->discountStorage->getDiscounts()
        );
    }

    public function addItem(Salable $item): self
    {
        $this->storage->addItem($item);
        return $this;
    }

    public function getItem(string $uuid): ?Salable
    {
        return $this->storage->getItem($uuid);
    }

    public function removeItem(Salable $item): self
    {
        $this->storage->removeItem($item);
        return $this;
    }

    public function getItems(): array
    {
        return $this->storage->getItems();
    }

    public function getTotalAmountWithoutDiscounts(): int
    {
        return array_reduce($this->storage->getItems(), fn (int $total, Salable $item) => $total + $item->getPrice(), 0);
    }
}
