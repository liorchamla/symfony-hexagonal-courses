<?php

namespace Domain\Basket\UseCase\RequestModel;

use Spatie\DataTransferObject\DataTransferObject;

class AddDiscountRequest extends DataTransferObject
{
    public string $uuid;
}
