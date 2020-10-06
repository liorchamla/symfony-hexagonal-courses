<?php

namespace App\Tests\Domain\Courses\UseCase;

use App\Tests\Domain\Courses\Adapters\InMemoryChapterRepository;
use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use App\Tests\Domain\Courses\Factory\ChapterFactory;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use Domain\Courses\Exception\ChapterNotFoundException;
use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\UseCase\DeleteChapter;
use Domain\Courses\UseCase\DeleteCourse;
use Domain\Courses\UseCase\RequestModel\DeleteChapterRequest;
use Domain\Courses\UseCase\RequestModel\DeleteCourseRequest;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertNull;

it('should delete a chapter if it exists', function () {

    $chapterRepository = new InMemoryChapterRepository;

    // Given there is a course in the database
    $chapter = ChapterFactory::create();
    $chapterRepository->store($chapter);

    // When we want to delete it
    $useCase = new DeleteChapter($chapterRepository);
    $useCase->execute(new DeleteChapterRequest([
        'uuid' => $chapter->uuid
    ]));

    // Then it should not be in the database anymore
    assertNull($chapterRepository->findOneOrNull($chapter->uuid));
});

it('should throw if we try to delete an unexisting chapter', function () {
    $chapterRepository = new InMemoryChapterRepository;

    // Given there is no course in the database
    // When we want to delete it
    // Then it should throw
    $useCase = new DeleteChapter($chapterRepository);
    $useCase->execute(new DeleteChapterRequest([
        'uuid' => 'unexisting-uuid'
    ]));
})->throws(ChapterNotFoundException::class);
