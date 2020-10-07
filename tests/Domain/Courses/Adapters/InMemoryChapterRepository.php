<?php

namespace App\Tests\Domain\Courses\Adapters;

use Domain\Courses\Entity\Chapter;
use Domain\Courses\Gateway\ChapterRepositoryInterface;


class InMemoryChapterRepository implements ChapterRepositoryInterface
{
    /**
     * 
     *
     * @var Chapter[] 
     */
    public array $chapters = [];

    public function store(Chapter $chapter): void
    {
        $this->chapters[$chapter->uuid] = $chapter;
    }

    public function findOneOrNull(string $uuid): ?Chapter
    {
        return $this->chapters[$uuid] ?? null;
    }

    public function findChaptersForCourse(string $courseUuid): array
    {
        return array_filter($this->chapters, fn (Chapter $c) => $c->course->uuid === $courseUuid);
    }

    public function delete(Chapter $chapter): void
    {
        unset($this->chapters[$chapter->uuid]);
    }
}
