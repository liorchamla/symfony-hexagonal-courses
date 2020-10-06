<?php

namespace Domain\Courses\UseCase\ViewModel;

use Domain\Courses\Entity\Course;

class DeleteCourseViewModel
{
    public Course $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }
}
