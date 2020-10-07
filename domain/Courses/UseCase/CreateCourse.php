<?php

namespace Domain\Courses\UseCase;

use Domain\Courses\Entity\Course;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Domain\Courses\UseCase\RequestModel\CreateCourseRequest;
use Domain\Courses\UseCase\ViewModel\CreateCourseViewModel;

class CreateCourse
{
    protected CourseRepositoryInterface $courseRepository;
    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(CreateCourseRequest $request): CreateCourseViewModel
    {
        $course = $request->toCourse();

        $this->courseRepository->store($course);

        return new CreateCourseViewModel($course);
    }
}
