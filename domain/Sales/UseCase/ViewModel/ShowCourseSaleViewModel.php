<?php

namespace Domain\Sales\UseCase\ViewModel;

use Domain\Courses\Entity\Course;

class ShowCourseSaleViewModel
{
    public iterable $sales = [];
    public Course $course;

    public function __construct(Course $course, iterable $sales = [])
    {
        $this->course = $course;
        $this->sales = $sales;
    }
}
