<?php

namespace Domain\Courses\Entity;

use Ramsey\Uuid\Uuid;

class Chapter
{
    public string $uuid;
    public Course $course;
    public string $title;

    public function __construct(Course $course, string $title)
    {
        $this->course = $course;
        $this->title = $title;
        $this->uuid = Uuid::uuid1();
    }
}
