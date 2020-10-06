<?php

namespace Domain\Basket\UseCase\RequestModel;

use Domain\Sales\Entity\Salable;
use Spatie\DataTransferObject\DataTransferObject;

class AddSalableToBasketRequest extends DataTransferObject
{
    public string $salable_uuid;
    public string $salable_class;
}
