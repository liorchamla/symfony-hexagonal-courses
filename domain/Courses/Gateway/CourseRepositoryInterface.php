<?php

namespace Domain\Courses\Gateway;

use Domain\Courses\Entity\Course;

interface CourseRepositoryInterface
{
    public function findOneOrNull(string $uuid): ?Course;
    public function store(Course $course): void;
    public function delete(Course $course): void;
}
