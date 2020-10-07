<?php

namespace Domain\Courses\UseCase\RequestModel;

use Domain\Courses\Entity\Course;
use Spatie\DataTransferObject\DataTransferObject;

class EditCourseRequest extends DataTransferObject
{
    public string $title;
    public int $price;
    public string $uuid;

    public function toCourse(): Course
    {
        return new Course($this->title, $this->price, $this->uuid);
    }
}
