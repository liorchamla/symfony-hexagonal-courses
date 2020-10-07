<?php

namespace Domain\Courses\UseCase\ViewModel;

use Domain\Courses\Entity\Chapter;
use Domain\Courses\Entity\Course;

class ShowCourseViewModel
{
    public Course $course;

    /**
     * 
     *
     * @var array<int, Chapter> 
     */
    public array $chapters = [];

    public function __construct(Course $course, array $chapters = [])
    {
        $this->course = $course;
        $this->chapters = $chapters;
    }
}
