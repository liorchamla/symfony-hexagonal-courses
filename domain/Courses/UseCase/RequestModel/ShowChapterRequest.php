<?php

namespace Domain\Courses\UseCase\RequestModel;

use Spatie\DataTransferObject\DataTransferObject;

class ShowChapterRequest extends DataTransferObject
{
    public string $uuid;
    public bool $isAuthenticated = false;
    public ?string $userUuid;
}
