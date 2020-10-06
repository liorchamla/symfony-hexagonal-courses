<?php

namespace App\Tests\Domain\Sales\Factory;

use App\Tests\Domain\Authentication\Factory\UserFactory;
use Domain\Sales\Entity\Discount;
use Domain\Sales\Entity\Sale;
use Faker\Factory;

class SaleFactory
{
    public static function create(array $data = []): Sale
    {
        $faker = Factory::create();

        $data = array_merge([
            'items' => [],
            'owner' => UserFactory::create()
        ], $data);

        $sale = new Sale($data['owner'], $data['items']);

        return $sale;
    }

    public static function createMany(int $count, array $data): array
    {
        $faker = Factory::create();
        $sales = [];

        for ($i = 0; $i < $count; $i++) {
            $data = array_merge([
                'items' => [],
                'owner' => UserFactory::create()
            ], $data);

            $sales[] = new Sale($data['owner'], $data['items']);
        }
        return $sales;
    }
}
