<?php

namespace App\Tests\Domain\Courses\Factory;

use Domain\Courses\Entity\Course;
use Faker\Factory;
use Faker\Generator;

class CourseFactory
{
    private static Generator $faker;

    private static function getFaker(): Generator
    {
        return self::$faker ?? Factory::create();
    }

    public static function create(array $data = []): ?Course
    {
        $faker = self::getFaker();

        $data = array_merge(
            [
            'title' => $faker->catchPhrase,
            'price' => $faker->numberBetween(100, 5000)
            ], $data
        );

        $course = new Course($data['title'], $data['price']);

        return $course;
    }

    /**
     * Undocumented function
     *
     * @param integer $count
     *
     * @return Course[]
     */
    public static function createMany(int $count, array $data = []): array
    {
        $faker = self::getFaker();

        $courses = [];

        for ($i = 0; $i < $count; $i++) {
            $data = array_merge(
                [
                'title' => $faker->catchPhrase,
                'price' => $faker->numberBetween(100, 5000)
                ], $data
            );

            $courses[] =  new Course($data['title'], $data['price']);
        }

        return $courses;
    }
}
