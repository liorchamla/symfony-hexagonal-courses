<?php

namespace Domain\Courses\UseCase;

use Domain\Courses\Entity\Course;
use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Domain\Courses\UseCase\RequestModel\EditCourseRequest;
use Domain\Courses\UseCase\ViewModel\EditCourseViewModel;

class EditCourse
{
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(EditCourseRequest $request): EditCourseViewModel
    {
        /**
* 
         *
 * @throws CourseNotFoundException 
*/
        $course = $this->fetchCourse($request->uuid);

        $this->applyUpdatesToCourse(
            $course,
            $request->except('uuid')->toArray()
        );

        $this->courseRepository->update($course);

        return new EditCourseViewModel($course);
    }

    protected function applyUpdatesToCourse(Course $course, array $data): void
    {
        foreach ($data as $field => $value) {
            if ($field === 'uuid') {
                continue;
            }

            $course->$field = $value;
        }
    }

    protected function fetchCourse(string $uuid): Course
    {
        $course = $this->courseRepository->findOneOrNull($uuid);

        if (null === $course) {
            throw new CourseNotFoundException(
                sprintf(
                    'Course with UUID %s was not found and cant be edited !',
                    $uuid
                )
            );
        }

        return $course;
    }
}
