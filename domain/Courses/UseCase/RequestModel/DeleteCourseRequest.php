<?php

namespace Domain\Courses\UseCase\RequestModel;

use Spatie\DataTransferObject\DataTransferObject;

class DeleteCourseRequest extends DataTransferObject
{
    public string $uuid;
}
