<?php

namespace Domain\Courses\UseCase\RequestModel;

use Spatie\DataTransferObject\DataTransferObject;

class ShowCourseRequest extends DataTransferObject
{
    public string $uuid;
    public bool $isAuthenticated = false;
}
