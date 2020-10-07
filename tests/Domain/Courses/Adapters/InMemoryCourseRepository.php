<?php

namespace App\Tests\Domain\Courses\Adapters;

use Domain\Courses\Entity\Course;
use Domain\Courses\Gateway\CourseRepositoryInterface;

class InMemoryCourseRepository implements CourseRepositoryInterface
{
    /**
     * 
     *
     * @var Course[] 
     */
    public array $courses = [];

    public function store(Course $course): void
    {
        $this->courses[$course->uuid] = $course;
    }

    public function findOneOrNull(string $uuid): ?Course
    {
        return $this->courses[$uuid] ?? null;
    }

    public function delete(Course $course): void
    {
        unset($this->courses[$course->uuid]);
    }

    public function update(Course $course): void
    {
        $this->courses[$course->uuid] = $course;
    }
}
