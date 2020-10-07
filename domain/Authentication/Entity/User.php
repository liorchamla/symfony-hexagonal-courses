<?php

namespace Domain\Authentication\Entity;

use Domain\Courses\Entity\Course;
use Ramsey\Uuid\Uuid;

class User
{
    public string $uuid;

    /**
     * 
     *
     * @var Course[] 
     */
    public array $courses = [];

    public function __construct(array $courses = [])
    {
        $this->courses = $courses;
        $this->uuid = Uuid::uuid1();
    }

    public function addCourse(Course $course): self
    {
        $this->courses[$course->uuid] = $course;
        return $this;
    }
}
