<?php

namespace Domain\Basket\UseCase\RequestModel;

use Spatie\DataTransferObject\DataTransferObject;

class RemoveSalableFromBasketRequest extends DataTransferObject
{
    public string $salable_uuid;
}
