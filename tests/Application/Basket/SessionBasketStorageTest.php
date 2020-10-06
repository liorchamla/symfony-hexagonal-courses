<?php

namespace App\Tests\Application\Basket;

use Domain\Courses\Entity\Course;
use App\Tests\Domain\WithFactoryTrait;
use App\Tests\Application\WithClientTrait;
use App\Basket\Adapter\SessionBasketStorage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionBasketStorageTest extends WebTestCase
{

    use WithClientTrait;
    use WithFactoryTrait;

    protected CourseRepositoryInterface $courseRepository;

    protected function setUp()
    {
        $this->setUpClient();

        $this->courseRepository = self::$container->get(CourseRepositoryInterface::class);
    }

    /** @test */
    public function storage_impact_session_on_add()
    {
        /** @var SessionInterface */
        $session = self::$container->get(SessionInterface::class);

        // Given we have a course
        $course = $this->createCourse(['price' => 400]);

        // When we add it in the storage
        $storage = new SessionBasketStorage($session);
        $storage->addItem($course);

        // Then we should find the course in the storage
        $this->assertCount(1, $session->get('basket.items'));
        $this->assertEquals($course, $session->get('basket.items')[$course->uuid]);
    }

    /** @test */
    public function storage_impact_session_on_delete()
    {
        /** @var SessionInterface */
        $session = self::$container->get(SessionInterface::class);

        // Given we have a course
        $course = $this->createCourse(['price' => 400]);

        // And the course is already in the storage
        $session->set('basket.items', [
            $course->uuid => $course
        ]);

        // When we remove it from the storage
        $storage = new SessionBasketStorage($session);
        $storage->removeItem($course);

        // Then the basket should be empty
        $this->assertCount(0, $session->get('basket.items'));
    }
}
