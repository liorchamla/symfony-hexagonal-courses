<?php

namespace Domain\Basket\Gateway;

use Domain\Basket\Entity\Discount;

interface DiscountStorageInterface
{
    public function addDiscount(Discount $discount);
    public function getDiscounts(): array;
}
