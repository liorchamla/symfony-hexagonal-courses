<?php

namespace App\Tests\Application\Basket\Controller;

use App\Courses\Adapter\DoctrineCourseRepository;
use App\Tests\Application\WithClientTrait;
use App\Tests\Domain\WithFactoryTrait;
use Domain\Basket\BasketManager;
use Domain\Basket\Discount\DiscountComputation;
use Domain\Basket\Gateway\BasketStorageInterface;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RemoveFromBasketControllerTest extends WebTestCase
{
    use WithClientTrait;
    use WithFactoryTrait;

    protected CourseRepositoryInterface $courseRepository;
    protected BasketManager $basketManager;
    protected BasketStorageInterface $basketStorage;

    protected function setUp()
    {
        $this->setUpClient();

        $this->courseRepository = self::$container->get(CourseRepositoryInterface::class);
        $this->basketStorage = self::$container->get(BasketStorageInterface::class);
        $this->basketManager = self::$container->get(BasketManager::class);
    }

    /** @test */
    public function we_can_remove_a_course_from_basket()
    {
        // Given there is a course
        $course = $this->createCourse(['price' => 400]);

        // And it is already in the basket
        $this->basketManager->addItem($course);

        // When we call the controller
        $this->get('remove_from_basket', [
            'uuid' => $course->uuid
        ]);

        $messages = $this->flashBag->get('success');

        // Then the response should be a redirection
        $this->assertResponseStatusCodeSame(302);

        // And the course should be added to the storage
        $this->assertNull($this->basketStorage->getItem($course->uuid));
        $this->assertCount(1, $messages);
    }

    /** @test */
    public function we_cant_remove_a_course_from_basket_if_it_is_not_inside()
    {
        // Given there is no course in the basket
        // When we call the controller
        $this->get('remove_from_basket', [
            'uuid' => 'unexisting-uuid'
        ]);

        $messages = $this->flashBag->get('danger');
        $successMessages = $this->flashBag->get('success');

        // Then the response should be a redirection
        $this->assertResponseStatusCodeSame(302);

        // And the course should be added to the storage
        $this->assertNull($this->basketStorage->getItem('unexisting-uuid'));

        $this->assertCount(1, $messages);
        $this->assertCount(0, $successMessages);
    }
}
