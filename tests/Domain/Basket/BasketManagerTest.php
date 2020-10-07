<?php

namespace App\Tests\Domain\Basket;

use PHPUnit\Framework\TestCase;
use Domain\Basket\BasketManager;
use Domain\Courses\Entity\Course;
use Domain\Basket\Entity\Discount;
use Domain\Basket\Discount\DiscountComputation;

use App\Tests\Domain\Courses\Factory\CourseFactory;
use App\Tests\Domain\Sales\Factory\DiscountFactory;
use App\Tests\Domain\Basket\Adapters\InMemoryBasketStorage;
use App\Tests\Domain\Basket\Adapters\InMemoryDiscountStorage;
use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;

class BasketManagerTest extends TestCase
{
    /**
     * 
     *
     * @test 
     */
    public function user_can_add_course_to_basket()
    {
        // And we have 2 specific course
        $course1 = CourseFactory::create(['price' => mt_rand(100, 300)]);
        $course2 = CourseFactory::create(['price' => mt_rand(100, 300)]);

        // And they are well stored
        $courseRepository = new InMemoryCourseRepository;
        $courseRepository->store($course1);
        $courseRepository->store($course2);

        // And we have a basket manager 
        $basketStorage = new InMemoryBasketStorage;
        $discountComputation = new DiscountComputation;
        $basketManager = new BasketManager($basketStorage, $discountComputation, new InMemoryDiscountStorage);

        // When we add a course to the basket
        $basketManager->addItem($course1)->addItem($course2);

        // Then the basket should contain the course
        $this->assertContains($course1, $basketManager->getItems());
        // And the BasketStorage should know about it
        $this->assertContains($course1, $basketStorage->getItems());
        // And the basket should know about his total amount
        $this->assertEquals($course1->getPrice() + $course2->getPrice(), $basketManager->getTotalAmountWithoutDiscounts());
    }

    /** 
     * @test 
     * @dataProvider provide_courses_and_discounts
     */
    public function user_can_add_global_discounts_to_basket($courses, $discounts, $expectedTotal)
    {
        // And we have 2 specific course
        $course1 = CourseFactory::create(['price' => mt_rand(100, 300)]);
        $course2 = CourseFactory::create(['price' => mt_rand(100, 300)]);

        // And they are well stored
        $courseRepository = new InMemoryCourseRepository;
        $courseRepository->store($course1);
        $courseRepository->store($course2);

        // And we have a basket manager 
        $basketStorage = new InMemoryBasketStorage;
        $discountComputation = new DiscountComputation;
        $basketManager = new BasketManager($basketStorage, $discountComputation, new InMemoryDiscountStorage);

        // When we add a course to the basket
        array_walk($courses, fn (Course $c) => $basketManager->addItem($c));

        // And we add a global discount to the basket
        array_walk($discounts, fn (Discount $d) => $basketManager->applyDiscount($d));

        // Then the basket total amount with discounts should be correct
        $this->assertEquals($expectedTotal, $basketManager->getTotalAmountWithDiscounts());
    }

    public function provide_courses_and_discounts()
    {
        yield [
            [CourseFactory::create(['price' => 500]), CourseFactory::create(['price' => 300])],
            [
                DiscountFactory::create(
                    [
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_FIXED,
                    'value' => 100
                    ]
                ),
                DiscountFactory::create(
                    [
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_FIXED,
                    'value' => 300
                    ]
                )
            ],
            100
        ];

        yield [
            [CourseFactory::create(['price' => 500]), CourseFactory::create(['price' => 300])],
            [
                DiscountFactory::create(
                    [
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_FIXED,
                    'value' => 100
                    ]
                ),
                DiscountFactory::create(
                    [
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_PERCENT,
                    'value' => 10
                    ]
                )
            ],
            540
        ];

        yield [
            [CourseFactory::create(['price' => 500]), CourseFactory::create(['price' => 300])],
            [
                DiscountFactory::create(
                    [
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_PERCENT,
                    'value' => 10
                    ]
                ),
                DiscountFactory::create(
                    [
                    'scope' => Discount::SCOPE_GLOBAL,
                    'type' => Discount::TYPE_FIXED,
                    'value' => 100
                    ]
                ),
            ],
            540
        ];

        $course1 = CourseFactory::create(['price' => 500]);
        $course2 = CourseFactory::create(['price' => 300]);

        yield [
            [$course1, $course2],
            [
                DiscountFactory::create(
                    [
                    'scope' => Discount::SCOPE_SPECIFIC,
                    'type' => Discount::TYPE_PERCENT,
                    'value' => 10,
                    'items' => [
                        $course1,
                        $course2
                    ]
                    ]
                ),
                DiscountFactory::create(
                    [
                    'scope' => Discount::SCOPE_SPECIFIC,
                    'type' => Discount::TYPE_FIXED,
                    'value' => 100,
                    'items' => [
                        $course1
                    ]
                    ]
                ),
            ],
            630
        ];
    }
}
