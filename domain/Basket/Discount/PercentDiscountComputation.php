<?php

namespace Domain\Basket\Discount;


class PercentDiscountComputation
{
    public function getDiscountedAmount(int $total, int $percent): int
    {
        return $total * $percent / 100;
    }
}
