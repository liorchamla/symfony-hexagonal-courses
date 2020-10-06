<?php

namespace Domain\Sales\UseCase\RequestModel;

use Spatie\DataTransferObject\DataTransferObject;

class PlaceOrderRequest extends DataTransferObject
{
    public bool $isAuthenticated = false;
}
