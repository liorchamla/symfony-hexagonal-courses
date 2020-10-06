<?php

namespace Domain\Sales\UseCase\RequestModel;

use Domain\Authentication\Entity\User;
use Spatie\DataTransferObject\DataTransferObject;

class ShowUserSalesRequest extends DataTransferObject
{
    public string $uuid;
}
