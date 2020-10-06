<?php

namespace App\Tests\Domain\Basket\Adapters;

use Domain\Basket\Entity\Discount;
use Domain\Basket\Gateway\DiscountRepositoryInterface;


class InMemoryDiscountRepository implements DiscountRepositoryInterface
{
    /** @var Discount[] */
    public array $discounts = [];

    public function store(Discount $discount)
    {
        $this->discounts[$discount->uuid] = $discount;
    }

    public function findOneOrNull(string $uuid): ?Discount
    {
        return $this->discounts[$uuid] ?? null;
    }

    public function decrementDiscountMaximumUses(Discount $discount)
    {
        $discount->maximumUses--;
        $this->store($discount);
    }
}
