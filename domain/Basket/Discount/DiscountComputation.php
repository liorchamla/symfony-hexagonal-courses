<?php

namespace Domain\Basket\Discount;

use Domain\Basket\Entity\Discount;
use Domain\Sales\Entity\Salable;


class DiscountComputation
{

    /**
     * Undocumented function
     *
     * @param Salable[] $salables
     * @param Discount[] $specificDiscounts
     *
     * @return DiscountsMetadataList
     */
    public function computeAppliedDiscountsMetadata(array $salables, array $discounts): DiscountsMetadataList
    {
        $metadatas = new DiscountsMetadataList;

        foreach ($salables as $salable) {
            $specificDiscounts = $this->findSpecificDiscounts($salable, $discounts);

            foreach ($specificDiscounts as $d) {
                $metadatas->addDiscountMetadata($salable, $d);
            }

            $globalDiscounts = $this->findGlobalDiscounts($discounts);

            foreach ($globalDiscounts as $d) {
                $metadatas->addDiscountMetadata($salable, $d);
            }
        }

        return $metadatas;
    }

    /**
     * Undocumented function
     *
     * @param Discount[] $discounts
     *
     * @return Discount[]
     */
    public function findGlobalDiscounts(array $discounts): array
    {
        return array_filter($discounts, fn (Discount $d) => $d->scope === Discount::SCOPE_GLOBAL);
    }

    /**
     * Undocumented function
     *
     * @param Salable $salable
     * @param Discount[] $specificDiscounts
     *
     * @return Discount[]
     */
    public function findSpecificDiscounts(Salable $salable, array $specificDiscounts): array
    {
        return array_filter($specificDiscounts, fn (Discount $d) => in_array($salable, $d->items));
    }

    /**
     * Undocumented function
     *
     * @param Salable[] $salables
     * @param Discount[] $discounts
     *
     * @return DiscountsMetadataList
     */
    public function computeDiscounts(array $salables, array $discounts): DiscountsMetadataList
    {
        return $this->computeAppliedDiscountsMetadata($salables, $discounts);
    }

    /**
     * Undocumented function
     *
     * @param Salable[] $salables
     * @param Discount[] $discounts
     *
     * @return integer
     */
    public function getComputedTotalAmount(array $salables, array $discounts): int
    {
        $total = 0;

        foreach ($salables as $s) {
            $total += $this->getComputedTotalAmountForSalable($s, $discounts);
        }

        return $total;
    }

    public function getComputedTotalAmountForSalable(Salable $s, array $discounts): int
    {
        $discountsMetadata = $this->computeDiscounts([$s], $discounts);

        $salableDiscountsMetadata = $discountsMetadata->getDiscountsMetadataForSalable($s);

        $initialPrice = $this->getOverallFixedDiscountsAmount($s->getPrice(), $salableDiscountsMetadata);

        $initialPrice = $this->getOverallPercentDiscountsAmount($initialPrice, $salableDiscountsMetadata);

        return $initialPrice;
    }

    protected function getOverallFixedDiscountsAmount(int $initialPrice, array $discountsMetadata): int
    {
        $fixedDiscountsMetadata = array_filter($discountsMetadata, fn (AppliedDiscountMetadata $metadata) => $metadata->discount && $metadata->discount->type === Discount::TYPE_FIXED);


        foreach ($fixedDiscountsMetadata as $metadata) {
            $initialPrice -= $metadata->getDiscountedAmount($initialPrice);
        }

        return $initialPrice;
    }

    protected function getOverallPercentDiscountsAmount(int $initialPrice, array $discountsMetadata): int
    {
        $percentDiscountsMetadata = array_filter($discountsMetadata, fn (AppliedDiscountMetadata $metadata) => $metadata->discount && $metadata->discount->type === Discount::TYPE_PERCENT);;

        foreach ($percentDiscountsMetadata as $metadata) {
            $initialPrice -= $metadata->getDiscountedAmount($initialPrice);
        }

        return $initialPrice;
    }
}
