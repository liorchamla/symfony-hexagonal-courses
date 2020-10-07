<?php

namespace App\Tests\Domain\Basket\UseCase;

use PHPUnit\Framework\TestCase;
use Domain\Basket\BasketManager;
use Domain\Basket\Entity\Discount;
use Domain\Basket\UseCase\AddDiscount;
use Domain\Basket\Discount\DiscountComputation;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use App\Tests\Domain\Sales\Factory\DiscountFactory;
use Domain\Sales\Exception\DiscountNotFoundException;
use App\Tests\Domain\Authentication\Factory\UserFactory;
use Domain\Basket\UseCase\RequestModel\AddDiscountRequest;
use App\Tests\Domain\Basket\Adapters\InMemoryBasketStorage;
use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use App\Tests\Domain\Basket\Adapters\InMemoryDiscountRepository;
use App\Tests\Domain\Authentication\Adapters\InMemoryUserRepository;
use App\Tests\Domain\Basket\Adapters\InMemoryDiscountStorage;

class AddDiscountTest extends TestCase
{

    /** @test */
    public function applying_an_unexisting_discount_will_throw()
    {
        // Given we call the use case with an unexisting discount
        // When we call the use case
        // Then it should throw
        $this->expectException(DiscountNotFoundException::class);
        $useCase = new AddDiscount(new InMemoryDiscountRepository, new BasketManager(new InMemoryBasketStorage, new DiscountComputation, new InMemoryDiscountStorage));
        $view = $useCase->execute(new AddDiscountRequest([
            'uuid' => 'unexisting-discount-uuid'
        ]));
    }

    /** 
     * @test 
     * @dataProvider provide_courses_and_discounts
     */
    public function user_can_add_a_global_discount_to_the_basket(array $courses, array $discounts, int $expectedTotal)
    {
        // Given we have a user
        $user = UserFactory::create();

        // And it is well stored
        $userRepository = new InMemoryUserRepository;
        $userRepository->store($user);

        // And courses are well stored
        $courseRepository = new InMemoryCourseRepository;


        // And the user add courses to his basket
        $basketManager = new BasketManager(new InMemoryBasketStorage, new DiscountComputation, new InMemoryDiscountStorage);
        $basketManager->setOwner($user);

        foreach ($courses as $c) {
            $courseRepository->store($c);
            $basketManager->addItem($c);
        }

        $discountRepository = new InMemoryDiscountRepository;

        foreach ($discounts as $d) {
            $discountRepository->store($d);
            $beforeMaximumUses = $d->maximumUses;

            $useCase = new AddDiscount($discountRepository, $basketManager);
            $view = $useCase->execute(new AddDiscountRequest([
                'uuid' => $d->uuid
            ]));

            $this->assertEquals($beforeMaximumUses - 1, $d->maximumUses);
        }

        // When we call the use case
        $this->assertCount(count($discounts), $basketManager->getAppliedDiscounts());

        // Then the maximum uses of the Discount should be decremented

        $this->assertEquals(
            $expectedTotal,
            $basketManager->getTotalAmountWithDiscounts()
        );
    }


    public function provide_courses_and_discounts()
    {
        yield [
            CourseFactory::createMany(3, ['price' => 300]),
            [
                DiscountFactory::create([
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_FIXED,
                    'value' => 100
                ]),
                DiscountFactory::create([
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_FIXED,
                    'value' => 50
                ]),
            ],
            450
        ];

        yield [
            CourseFactory::createMany(3, ['price' => 300]),
            [
                DiscountFactory::create([
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_FIXED,
                    'value' => 100
                ]),
            ],
            600
        ];

        yield [
            CourseFactory::createMany(3, ['price' => 300]),
            [
                DiscountFactory::create([
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_PERCENT,
                    'value' => 20
                ]),
            ],
            720
        ];

        $course1 = CourseFactory::create(['price' => 600]);
        $course2 = CourseFactory::create(['price' => 600]);

        yield [
            [$course1, $course2],
            [
                DiscountFactory::create([
                    'scope' => Discount::SCOPE_SPECIFIC,
                    'type' => Discount::TYPE_PERCENT,
                    'value' => 20,
                    'items' => [$course1]
                ]),
            ],
            1200 - 120
        ];

        yield [
            [$course1, $course2],
            [
                DiscountFactory::create([
                    'scope' => Discount::SCOPE_SPECIFIC,
                    'type' => Discount::TYPE_PERCENT,
                    'value' => 20,
                    'items' => [$course1]
                ]),
                DiscountFactory::create([
                    'scope' => Discount::SCOPE_SPECIFIC,
                    'type' => Discount::TYPE_FIXED,
                    'value' => 50,
                    'items' => [$course1, $course2]
                ]),
            ],
            1200 - 50 - 50 - 110
        ];
    }
}
