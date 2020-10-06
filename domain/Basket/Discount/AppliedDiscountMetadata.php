<?php

namespace Domain\Basket\Discount;

use Domain\Basket\Entity\Discount;
use Domain\Sales\Entity\Salable;
use Spatie\DataTransferObject\DataTransferObject;

class AppliedDiscountMetadata extends DataTransferObject
{
    public Salable $salable;
    public ?Discount $discount;

    public function getDiscountedAmount(int $initialPrice): int
    {
        if (null === $this->discount) {
            return 0;
        }

        $computer = $this->discount->type === Discount::TYPE_FIXED ?
            new FixedDiscountComputation :
            new PercentDiscountComputation;

        return $computer->getDiscountedAmount($initialPrice, $this->discount->value);
    }
}
