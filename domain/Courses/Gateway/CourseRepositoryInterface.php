<?php

namespace Domain\Courses\Gateway;

use Domain\Courses\Entity\Course;

interface CourseRepositoryInterface
{
    public function findOneOrNull(string $uuid): ?Course;
    public function store(Course $course);
    public function delete(Course $course);
}
