<?php

namespace App\Tests\Domain\Courses\UseCase;

use App\Tests\Domain\Authentication\Adapters\InMemoryUserRepository;
use App\Tests\Domain\Authentication\Factory\UserFactory;
use App\Tests\Domain\Courses\Adapters\InMemoryChapterRepository;
use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use App\Tests\Domain\Courses\Factory\ChapterFactory;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use Domain\Courses\Exception\ChapterNotFoundException;
use Domain\Courses\UseCase\RequestModel\ShowChapterRequest;
use Domain\Courses\UseCase\ShowChapter;
use Domain\Courses\UseCase\ViewModel\ShowChapterViewModel;
use PHPUnit\Framework\TestCase;

class ShowChapterTest extends TestCase
{
    /** @test */
    public function everyone_can_see_a_chapter_but_cant_play()
    {
        // Given there is a specific chapter
        $chapter = ChapterFactory::create();
        // And it is well stored
        $chapterRepository = new InMemoryChapterRepository;
        $chapterRepository->store($chapter);

        // When we call the use case
        $useCase = new ShowChapter($chapterRepository, new InMemoryUserRepository);
        $view = $useCase->execute(new ShowChapterRequest([
            'uuid' => $chapter->uuid
        ]));

        // Then we should find chapter's infos in the viewmodel
        $this->assertInstanceOf(ShowChapterViewModel::class, $view);
        $this->assertEquals($chapter, $view->chapter);
        $this->assertFalse($view->canPlayChapter);
        $this->assertNotNull($view->chapter->course);
    }

    /** @test */
    public function an_unexisting_chapter_cant_be_shown()
    {
        // Given we want to show an unexisting chapter
        $chapterRepository = new InMemoryChapterRepository;

        // When we call the use case
        // Then it should throw an exception
        $this->expectException(ChapterNotFoundException::class);

        $useCase = new ShowChapter($chapterRepository, new InMemoryUserRepository);
        $view = $useCase->execute(new ShowChapterRequest([
            'uuid' => 'inexistant-chapter-uuid'
        ]));
    }

    /** @test */
    public function customers_who_paid_the_chapter_course_can_play()
    {
        // Given there is a specific course 
        $course = CourseFactory::create(['title' => 'Symfony 5']);
        // And it is well stored
        $courseRepository = new InMemoryCourseRepository;
        $courseRepository->store($course);

        // And the user bought this course
        $user = UserFactory::create([
            'courses' => [$course]
        ]);
        $userRepository = new InMemoryUserRepository;
        $userRepository->store($user);

        // And we have a specific chapter of this course
        $chapter = ChapterFactory::create(['course' => $course]);

        // And it is well stored
        $chapterRepository = new InMemoryChapterRepository;
        $chapterRepository->store($chapter);

        // When we call the use case
        $useCase = new ShowChapter($chapterRepository, $userRepository);
        $view = $useCase->execute(new ShowChapterRequest([
            'uuid' => $chapter->uuid,
            'userUuid' => $user->uuid
        ]));

        // Then we should find chapter's infos in the viewmodel
        $this->assertInstanceOf(ShowChapterViewModel::class, $view);
        $this->assertEquals($chapter, $view->chapter);
        $this->assertTrue($view->canPlayChapter);
    }
}
