<?php

namespace Domain\Courses\UseCase;

use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\Gateway\ChapterRepositoryInterface;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Domain\Courses\UseCase\RequestModel\ShowCourseRequest;
use Domain\Courses\UseCase\ViewModel\ShowCourseViewModel;

class ShowCourse
{
    protected CourseRepositoryInterface $courseRepository;
    protected ChapterRepositoryInterface $chapterRepository;

    public function __construct(CourseRepositoryInterface $courseRepository, ChapterRepositoryInterface $chapterRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->chapterRepository = $chapterRepository;
    }

    public function execute(ShowCourseRequest $request): ShowCourseViewModel
    {
        $course = $this->courseRepository->findOneOrNull($request->uuid);

        if (null === $course) {
            throw new CourseNotFoundException(
                sprintf('The course with UUID %s could not be found !', $request->uuid)
            );
        }

        return new ShowCourseViewModel($course, $this->chapterRepository->findChaptersForCourse($course->uuid));
    }
}
