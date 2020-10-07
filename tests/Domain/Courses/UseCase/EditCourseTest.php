<?php

use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\UseCase\EditCourse;
use Domain\Courses\UseCase\RequestModel\EditCourseRequest;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

it(
    'should edit a course if it exists', function () {
        $courseRepository = new InMemoryCourseRepository;

        // Given we have a course
        $course = CourseFactory::create();
        $courseRepository->store($course);

        // When we call the use case
        $useCase = new EditCourse($courseRepository);
        $view = $useCase->execute(
            new EditCourseRequest(
                [
                'uuid' => $course->uuid,
                'title' => 'updated title',
                'price' => 100
                ]
            )
        );

        // And we fetch the course in the database
        $updatedCourse = $courseRepository->findOneOrNull($course->uuid);

        // Then it should be well updated
        assertNotNull($view->course);
        assertNotNull($updatedCourse);
        assertEquals('updated title', $view->course->title);
        assertEquals(100, $view->course->price);
    }
);

it(
    'should throw an exception if the course does not exist', function () {
        // Given we have no course
        $courseRepository = new InMemoryCourseRepository;

        // When we call the use case with a fake uuid
        (new EditCourse($courseRepository))->execute(
            new EditCourseRequest(
                [
                'uuid' => 'unexisting course',
                'title' => 'updated title',
                'price' => 100
                ]
            )
        );
    }
)->throws(CourseNotFoundException::class);
