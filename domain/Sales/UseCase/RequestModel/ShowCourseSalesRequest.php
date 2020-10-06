<?php

namespace Domain\Sales\UseCase\RequestModel;

use Spatie\DataTransferObject\DataTransferObject;

class ShowCourseSalesRequest extends DataTransferObject
{
    public string $uuid;
}
