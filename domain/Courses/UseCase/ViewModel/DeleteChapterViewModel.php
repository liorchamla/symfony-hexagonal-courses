<?php

namespace Domain\Courses\UseCase\ViewModel;

use Domain\Courses\Entity\Chapter;

class DeleteChapterViewModel
{
    public Chapter $chapter;

    public function __construct(Chapter $chapter)
    {
        $this->chapter = $chapter;
    }
}
