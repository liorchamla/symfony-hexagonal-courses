<?php

namespace Domain\Courses\Gateway;

use Domain\Courses\Entity\Chapter;

interface ChapterRepositoryInterface
{
    public function findOneOrNull(string $uuid): ?Chapter;
    public function store(Chapter $chapter): void;
    public function findChaptersForCourse(string $courseUuid): array;
    public function delete(Chapter $chapter): void;
}
