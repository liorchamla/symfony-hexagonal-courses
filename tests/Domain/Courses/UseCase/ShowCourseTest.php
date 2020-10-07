<?php

use App\Tests\Domain\Courses\Adapters\InMemoryChapterRepository;
use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use App\Tests\Domain\Courses\Factory\ChapterFactory;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\UseCase\RequestModel\ShowCourseRequest;
use Domain\Courses\UseCase\ShowCourse;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowCourseTest extends TestCase
{
    /**
     * 
     *
     * @test 
     */
    public function everyone_can_see_course_page()
    {
        // Given we have a course
        $title = 'Symfony 5';
        $course = CourseFactory::create(
            [
            'title' => $title
            ]
        );

        // And it is inside the store
        $courseRepository = new InMemoryCourseRepository();
        $courseRepository->store($course);

        // When I execute the ShowCourse UseCase
        $useCase = new ShowCourse($courseRepository, new InMemoryChapterRepository);
        $view = $useCase->execute(
            new ShowCourseRequest(
                [
                'uuid' => $course->uuid,
                'isAuthenticated' => false
                ]
            )
        );

        // Then the view should contain Course's informations
        $this->assertEquals($title, $view->course->title);
    }

    /**
     * 
     *
     * @test 
     */
    public function we_cant_see_an_unexisting_course()
    {
        // And it is inside the store
        $courseRepository = new InMemoryCourseRepository();

        // When I execute the ShowCourse UseCase
        $useCase = new ShowCourse($courseRepository, new InMemoryChapterRepository);

        $this->expectException(CourseNotFoundException::class);

        $view = $useCase->execute(
            new ShowCourseRequest(
                [
                'uuid' => 'inexistant'
                ]
            )
        );
    }

    /**
     * 
     *
     * @test 
     */
    public function we_can_find_the_chapters_list_for_a_course()
    {
        // Given we have a course
        $title = 'Symfony 5';
        $course = CourseFactory::create(
            [
            'title' => $title
            ]
        );

        // And it is inside the store
        $courseRepository = new InMemoryCourseRepository();
        $courseRepository->store($course);

        // And it holds chapters
        $chapter1 = ChapterFactory::create(['course' => $course]);
        $chapter2 = ChapterFactory::create(['course' => $course]);

        // And they are well stored
        $chapterRepository = new InMemoryChapterRepository;
        $chapterRepository->store($chapter1);
        $chapterRepository->store($chapter2);

        // When I execute the ShowCourse UseCase
        $useCase = new ShowCourse($courseRepository, $chapterRepository);
        $view = $useCase->execute(
            new ShowCourseRequest(
                [
                'uuid' => $course->uuid,
                'isAuthenticated' => false
                ]
            )
        );

        // Then the view should contain Course's informations
        $this->assertEquals($title, $view->course->title);
        // And chapters infos
        $this->assertCount(2, $view->chapters);
    }
}
