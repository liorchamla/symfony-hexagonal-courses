<?php

namespace Domain\Courses\UseCase\RequestModel;

use Domain\Courses\Entity\Course;
use Spatie\DataTransferObject\DataTransferObject;

class CreateCourseRequest extends DataTransferObject
{
    public string $title;
    public int $price;

    public function toCourse(): Course
    {
        return new Course($this->title, $this->price);
    }
}
