<?php

namespace App\Tests\Domain\Sales\Factory;

use App\Tests\Domain\Courses\Factory\CourseFactory;
use Faker\Factory;
use Domain\Basket\Entity\Discount;

class DiscountFactory
{
    public static function create(array $data = []): Discount
    {
        $faker = Factory::create();

        $scope =  $faker->randomElement([
            Discount::SCOPE_GLOBAL, Discount::SCOPE_SPECIFIC
        ]);

        $data = array_merge([
            'scope' => $scope,
            'type' => $faker->randomElement([
                Discount::TYPE_FIXED, Discount::TYPE_PERCENT
            ]),
            'value' => $faker->numberBetween(30, 50),
            'maximum_uses' => $faker->randomElement([
                5,
                Discount::MAXIMUM_USES_INFINITY
            ]),
            'items' => []
        ], $data);

        if ($data['scope'] === Discount::SCOPE_SPECIFIC && count($data['items']) === 0) {

            $data['items'] = [CourseFactory::create()];
        }

        $discount = new Discount($data['scope'], $data['type'], $data['value'], $data['maximum_uses'], $data['items']);

        return $discount;
    }
}
