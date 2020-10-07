<?php

namespace Domain\Courses\UseCase;

use Domain\Courses\Entity\Course;
use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Domain\Courses\UseCase\RequestModel\DeleteCourseRequest;
use Domain\Courses\UseCase\ViewModel\DeleteCourseViewModel;

class DeleteCourse
{
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(DeleteCourseRequest $request): DeleteCourseViewModel
    {
        $course = $this->courseRepository->findOneOrNull($request->uuid);

        if (null === $course) {
            throw new CourseNotFoundException(
                sprintf(
                    'Course with UUID %s was not found and can not be deleted !',
                    $request->uuid
                )
            );
        }

        $this->courseRepository->delete($course);

        return new DeleteCourseViewModel($course);
    }
}
