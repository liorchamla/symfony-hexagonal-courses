<?php

namespace Domain\Courses\Gateway;

use Domain\Courses\Entity\Chapter;

interface ChapterRepositoryInterface
{
    public function findOneOrNull(string $uuid): ?Chapter;
    public function store(Chapter $chapter);
    public function findChaptersForCourse(string $courseUuid): array;
}
