<?php

namespace Domain\Courses\UseCase\RequestModel;

use Spatie\DataTransferObject\DataTransferObject;

class DeleteChapterRequest extends DataTransferObject
{
    public string $uuid;
}
