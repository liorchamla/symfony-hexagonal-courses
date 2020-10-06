<?php

namespace App\Tests\Domain\Basket\Adapters;

use Domain\Basket\Entity\Discount;
use Domain\Basket\Gateway\DiscountStorageInterface;

class InMemoryDiscountStorage implements DiscountStorageInterface
{

    public array $discounts = [];

    public function addDiscount(Discount $discount)
    {
        $this->discounts[$discount->uuid] = $discount;
    }

    public function getDiscounts(): array
    {
        return $this->discounts;
    }
}
