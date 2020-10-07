<?php

namespace Domain\Courses\UseCase;

use Domain\Courses\Entity\Course;
use Domain\Courses\Exception\ChapterNotFoundException;
use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\Gateway\ChapterRepositoryInterface;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Domain\Courses\UseCase\RequestModel\DeleteChapterRequest;
use Domain\Courses\UseCase\RequestModel\DeleteCourseRequest;
use Domain\Courses\UseCase\ViewModel\DeleteChapterViewModel;
use Domain\Courses\UseCase\ViewModel\DeleteCourseViewModel;

class DeleteChapter
{
    protected ChapterRepositoryInterface $chapterRepository;

    public function __construct(ChapterRepositoryInterface $chapterRepository)
    {
        $this->chapterRepository = $chapterRepository;
    }

    public function execute(DeleteChapterRequest $request): DeleteChapterViewModel
    {
        $chapter = $this->chapterRepository->findOneOrNull($request->uuid);

        if (null === $chapter) {
            throw new ChapterNotFoundException(
                sprintf(
                    'Chapter with UUID %s was not found and can not be deleted !',
                    $request->uuid
                )
            );
        }

        $this->chapterRepository->delete($chapter);

        return new DeleteChapterViewModel($chapter);
    }
}
