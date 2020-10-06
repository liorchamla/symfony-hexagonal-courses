<?php

namespace Domain\Courses\UseCase\ViewModel;

use Domain\Courses\Entity\Chapter;

class ShowChapterViewModel
{
    public Chapter $chapter;
    public bool $canPlayChapter = false;

    public function __construct(Chapter $chapter, bool $canPlayChapter = false)
    {
        $this->chapter = $chapter;
        $this->canPlayChapter = $canPlayChapter;
    }
}
