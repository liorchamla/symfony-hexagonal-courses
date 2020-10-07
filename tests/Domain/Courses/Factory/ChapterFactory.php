<?php

namespace App\Tests\Domain\Courses\Factory;

use Domain\Courses\Entity\Chapter;
use Faker\Factory;

class ChapterFactory
{
    public static function create(array $data = []): Chapter
    {
        $faker = Factory::create();

        $data = array_merge(
            [
            'course' => CourseFactory::create(),
            'title' => $faker->catchPhrase
            ], $data
        );

        return new Chapter($data['course'], $data['title']);
    }
}
