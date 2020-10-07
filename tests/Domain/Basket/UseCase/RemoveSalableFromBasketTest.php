<?php

namespace App\Tests\Domain\Basket\UseCase;

use PHPUnit\Framework\TestCase;
use Domain\Basket\BasketManager;
use Domain\Courses\Entity\Course;
use App\Tests\Domain\WithFactoryTrait;
use Domain\Basket\UseCase\AddSalableToBasket;
use Domain\Basket\Discount\DiscountComputation;
use Domain\Basket\Gateway\BasketStorageInterface;
use Domain\Basket\UseCase\RemoveSalableFromBasket;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use App\Tests\Domain\Basket\Adapters\InMemoryBasketStorage;
use App\Tests\Domain\Basket\Adapters\InMemoryDiscountStorage;
use Domain\Basket\Exception\SalableNotFoundInBasketException;
use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use Domain\Basket\UseCase\RequestModel\AddSalableToBasketRequest;
use Domain\Basket\Exception\SalableCouldNotBeAddedToBasketException;
use Domain\Basket\UseCase\RequestModel\RemoveSalableFromBasketRequest;

class RemoveSalableFromBasketTest extends TestCase
{
    use WithFactoryTrait;

    protected BasketStorageInterface $basketStorage;
    protected BasketManager $basketManager;
    protected CourseRepositoryInterface $courseRepository;

    protected function setUp(): void
    {
        $this->basketStorage = new InMemoryBasketStorage;
        $this->basketManager = new BasketManager($this->basketStorage, new DiscountComputation, new InMemoryDiscountStorage);
        $this->courseRepository = new InMemoryCourseRepository;
    }

    /**
     * 
     *
     * @test 
     */
    public function we_can_remove_a_salable_from_basket()
    {
        // Given we have a course
        $course = $this->createCourse(['price' => 400]);

        // And it is already in the basket
        $this->basketStorage->addItem($course);

        // When we call the use case
        $useCase = new RemoveSalableFromBasket($this->basketManager, $this->courseRepository);
        $view = $useCase->execute(
            new RemoveSalableFromBasketRequest(
                [
                'salable_uuid' => $course->uuid
                ]
            )
        );

        // Then the course is added to the basket
        $this->assertEquals($view->salable, $course);
        $this->assertCount(0, $this->basketStorage->getItems());

        // And we can calculate basket total amount
        $this->assertEquals(0, $this->basketManager->getTotalAmountWithoutDiscounts());
    }

    /**
     * 
     *
     * @test 
     */
    public function adding_an_unexisting_salable_to_basket_will_throw()
    {
        // Given we have no course

        // When we call the use case
        // Then it will throw
        $this->expectException(SalableNotFoundInBasketException::class);
        $useCase = new RemoveSalableFromBasket($this->basketManager);
        $view = $useCase->execute(
            new RemoveSalableFromBasketRequest(
                [
                'salable_uuid' => 'unexistant-course-uuid'
                ]
            )
        );
    }
}
