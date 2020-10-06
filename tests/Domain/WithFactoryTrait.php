<?php

namespace App\Tests\Domain;

use Domain\Courses\Entity\Course;
use Domain\Courses\Entity\Chapter;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use App\Tests\Domain\Courses\Factory\ChapterFactory;
use Domain\Courses\Gateway\CourseRepositoryInterface;

trait WithFactoryTrait
{
    protected CourseRepositoryInterface $courseRepository;

    protected function createCourse(array $data = []): Course
    {
        if (empty($this->courseRepository)) {
            $this->courseRepository = self::$container->get(CourseRepositoryInterface::class);
        }

        $course = CourseFactory::create($data);
        $this->courseRepository->store($course);

        return $course;
    }

    protected function createCourses(int $count, array $data = []): array
    {
        $courses = [];
        for ($i = 0; $i < $count; $i++) {
            $courses[] = $this->createCourse($data);
        }

        return $courses;
    }

    protected function createChapter(array $data = []): Chapter
    {
        $chapter = ChapterFactory::create($data);
        $this->chapterRepository->store($chapter);

        return $chapter;
    }
}
