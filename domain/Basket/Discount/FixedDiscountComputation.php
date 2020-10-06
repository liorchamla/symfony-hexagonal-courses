<?php

namespace Domain\Basket\Discount;

use Domain\Sales\Entity\Salable;

class FixedDiscountComputation
{
    public function getDiscountedAmount(int $total, int $value): int
    {
        return $value > $total ? $total : $value;
    }
}
