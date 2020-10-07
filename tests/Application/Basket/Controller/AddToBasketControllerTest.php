<?php

namespace App\Tests\Application\Basket\Controller;

use App\Courses\Adapter\DoctrineCourseRepository;
use App\Tests\Application\WithClientTrait;
use App\Tests\Domain\WithFactoryTrait;
use Domain\Basket\BasketManager;
use Domain\Basket\Discount\DiscountComputation;
use Domain\Basket\Gateway\BasketStorageInterface;
use Domain\Basket\Gateway\DiscountStorageInterface;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddToBasketControllerTest extends WebTestCase
{
    use WithClientTrait;
    use WithFactoryTrait;

    protected CourseRepositoryInterface $courseRepository;
    protected BasketManager $basketManager;
    protected BasketStorageInterface $basketStorage;
    protected DiscountStorageInterface $discountStorage;

    protected function setUp(): void
    {
        $this->setUpClient();

        $this->courseRepository = self::$container->get(CourseRepositoryInterface::class);
        $this->basketStorage = self::$container->get(BasketStorageInterface::class);
        $this->discountStorage = self::$container->get(DiscountStorageInterface::class);
        $this->basketManager = new BasketManager($this->basketStorage, new DiscountComputation, $this->discountStorage);
    }

    /**
     * 
     *
     * @test 
     */
    public function we_can_add_a_course_to_basket()
    {
        // Given there is a course
        $course = $this->createCourse(['price' => 400]);

        // When we call the controller
        $this->get(
            'add_to_basket', [
            'uuid' => $course->uuid
            ]
        );

        // Then the response should be a redirection
        $this->assertResponseStatusCodeSame(302);

        // And the course should be added to the storage
        $this->assertNotNull($this->basketStorage->getItem($course->uuid));
        $this->assertEquals(400, $this->basketManager->getTotalAmountWithoutDiscounts());
        $this->assertCount(1, $this->flashBag->get('success'));
    }

    /**
     * 
     *
     * @test 
     */
    public function we_cant_add_a_course_to_basket_if_it_does_not_exist()
    {
        // Given there is no course in basket

        // When we call the controller
        $this->get(
            'add_to_basket', [
            'uuid' => 'unexisting-uuid'
            ]
        );

        // Then the response should be a redirection
        $this->assertResponseStatusCodeSame(302);

        // And the course should be added to the storage
        $this->assertNull($this->basketStorage->getItem('unexisting-uuid'));
        $this->assertEquals(0, $this->basketManager->getTotalAmountWithoutDiscounts());
        $this->assertCount(1, $this->flashBag->get('danger'));
        $this->assertCount(0, $this->flashBag->get('success'));
    }
}
