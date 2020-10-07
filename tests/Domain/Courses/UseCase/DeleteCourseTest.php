<?php

namespace App\Tests\Domain\Courses\UseCase;

use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\UseCase\DeleteCourse;
use Domain\Courses\UseCase\RequestModel\DeleteCourseRequest;
use PHPUnit\Framework\TestCase;

class DeleteCourseTest extends TestCase
{
    /**
     * 
     *
     * @test 
     */
    public function we_can_delete_a_course_if_it_exists()
    {
        $courseRepository = new InMemoryCourseRepository;

        // Given there is a course in the database
        $course = CourseFactory::create();
        $courseRepository->store($course);

        // When we want to delete it
        $useCase = new DeleteCourse($courseRepository);
        $useCase->execute(
            new DeleteCourseRequest(
                [
                'uuid' => $course->uuid
                ]
            )
        );

        // Then it should not be in the database anymore
        $this->assertNull($courseRepository->findOneOrNull($course->uuid));
    }

    /**
     * 
     *
     * @test 
     */
    public function we_cant_delete_a_course_if_it_does_not_exist()
    {
        $courseRepository = new InMemoryCourseRepository;

        // Given there is no course in the database
        // When we want to delete it
        // Then it should throw
        $this->expectException(CourseNotFoundException::class);
        $useCase = new DeleteCourse($courseRepository);
        $useCase->execute(
            new DeleteCourseRequest(
                [
                'uuid' => 'unexisting-uuid'
                ]
            )
        );
    }
}
