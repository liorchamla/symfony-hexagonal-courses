<?php

use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use Domain\Courses\Entity\Course;
use Domain\Courses\UseCase\CreateCourse;
use Domain\Courses\UseCase\RequestModel\CreateCourseRequest;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotNull;

it(
    "can create a course with good data", function () {
        // Setup :
        $courseRepository = new InMemoryCourseRepository;

        // Given we have good data
        $data = [
        'title' => "My Course Title",
        'price' => 4000
        ];

        // When we call the usecase
        $useCase = new CreateCourse($courseRepository);
        $view = $useCase->execute(new CreateCourseRequest($data));

        // Then a new course should be created
        assertInstanceOf(Course::class, $view->course);

        // And we can find it in the database
        assertNotNull($courseRepository->findOneOrNull($view->course->uuid));
    }
);
