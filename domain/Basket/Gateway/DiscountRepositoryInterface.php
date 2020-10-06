<?php

namespace Domain\Basket\Gateway;

use Domain\Basket\Entity\Discount;


interface DiscountRepositoryInterface
{
    public function store(Discount $discount);
    public function findOneOrNull(string $uuid): ?Discount;
    public function decrementDiscountMaximumUses(Discount $discount);
}
