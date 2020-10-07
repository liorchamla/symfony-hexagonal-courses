<?php

namespace Domain\Basket\Discount;

use Domain\Sales\Entity\Salable;
use Domain\Basket\Entity\Discount;

class DiscountsMetadataList
{
    public array $discountsMetadata = [];

    public function count(): int
    {
        return count($this->discountsMetadata);
    }

    public function addDiscountMetadata(Salable $salable, Discount $discount)
    {
        $uuid = $salable->getUuid();

        if (!array_key_exists($uuid, $this->discountsMetadata)) {
            $this->discountsMetadata[$uuid] = [];
        }

        $this->discountsMetadata[$uuid][] = new AppliedDiscountMetadata(
            [
            'salable' => $salable,
            'discount' => $discount
            ]
        );
    }

    public function getDiscountsMetadataForSalable(Salable $salable): array
    {
        return $this->discountsMetadata[$salable->getUuid()] ?? [];
    }
}
