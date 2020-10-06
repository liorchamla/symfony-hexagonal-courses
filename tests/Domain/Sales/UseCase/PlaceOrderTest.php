<?php

namespace App\Tests\Domain\Sales\UseCase;

use Domain\Sales\Entity\Sale;
use PHPUnit\Framework\TestCase;
use Domain\Basket\BasketManager;
use Domain\Sales\UseCase\PlaceOrder;
use Domain\Basket\Discount\DiscountComputation;
use App\Tests\Domain\Courses\Factory\CourseFactory;

use App\Tests\Domain\Authentication\Factory\UserFactory;
use Domain\Sales\UseCase\RequestModel\PlaceOrderRequest;
use App\Tests\Domain\Basket\Adapters\InMemoryBasketStorage;
use App\Tests\Domain\Sales\Adapters\InMemorySaleRepository;
use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use App\Tests\Domain\Authentication\Adapters\InMemoryUserRepository;
use App\Tests\Domain\Basket\Adapters\InMemoryDiscountStorage;
use Domain\Sales\Exception\OrderCouldNotBePlacedWithEmptyBasketException;
use Domain\Sales\Exception\OrderCouldNotBePlacedWithNoAuthenticatedUserException;

class PlaceOrderTest extends TestCase
{

    /** @test */
    public function an_unauthenticated_user_can_not_place_an_order()
    {
        // Given we have 2 specific course
        $course1 = CourseFactory::create(['price' => mt_rand(100, 300)]);
        $course2 = CourseFactory::create(['price' => mt_rand(100, 300)]);

        // And they are well stored
        $courseRepository = new InMemoryCourseRepository;
        $courseRepository->store($course1);
        $courseRepository->store($course2);

        // And the user add courses to his basket
        $basketManager = new BasketManager(new InMemoryBasketStorage, new DiscountComputation, new InMemoryDiscountStorage);
        $basketManager
            ->addItem($course1)
            ->addItem($course2);

        // When he places an order
        // Then an exception should be thrown
        $this->expectException(OrderCouldNotBePlacedWithNoAuthenticatedUserException::class);

        $saleRepository = new InMemorySaleRepository;
        $useCase = new PlaceOrder($basketManager, $saleRepository);
        $view = $useCase->execute(new PlaceOrderRequest([
            'isAuthenticated' => false
        ]));
    }

    /** @test */
    public function a_user_can_place_an_order()
    {
        // Given we have a user
        $user = UserFactory::create();

        // And it is well stored
        $userRepository = new InMemoryUserRepository;
        $userRepository->store($user);

        // And we have 2 specific course
        $course1 = CourseFactory::create(['price' => mt_rand(100, 300)]);
        $course2 = CourseFactory::create(['price' => mt_rand(100, 300)]);

        // And they are well stored
        $courseRepository = new InMemoryCourseRepository;
        $courseRepository->store($course1);
        $courseRepository->store($course2);

        // And the user add courses to his basket
        $basketManager = new BasketManager(new InMemoryBasketStorage, new DiscountComputation, new InMemoryDiscountStorage);
        $basketManager->setOwner($user)
            ->addItem($course1)
            ->addItem($course2);

        // When he places an order
        $saleRepository = new InMemorySaleRepository;
        $useCase = new PlaceOrder($basketManager, $saleRepository);
        $view = $useCase->execute(new PlaceOrderRequest([
            'isAuthenticated' => true
        ]));

        $this->assertInstanceOf(Sale::class, $view->sale);
        $this->assertEquals($course1->getPrice() + $course2->getPrice(), $view->sale->getTotalAmount());
        $this->assertTrue($userRepository->hasUserBoughtThisCourse($user->uuid, $course1->uuid));
        $this->assertTrue($userRepository->hasUserBoughtThisCourse($user->uuid, $course2->uuid));
        $this->assertNotNull($saleRepository->findOneOrNull($view->sale->uuid));
    }

    /** @test */
    public function a_user_can_not_place_order_if_basket_is_empty()
    {

        // Given we have a user
        $user = UserFactory::create();

        // And it is well stored
        $userRepository = new InMemoryUserRepository;
        $userRepository->store($user);

        // And the user has no course in the basket
        $basketManager = new BasketManager(new InMemoryBasketStorage, new DiscountComputation, new InMemoryDiscountStorage);
        $basketManager->setOwner($user);

        // When he places an order
        // Then an exception should be thrown

        $this->expectException(OrderCouldNotBePlacedWithEmptyBasketException::class);

        $saleRepository = new InMemorySaleRepository;
        $useCase = new PlaceOrder($basketManager, $saleRepository);
        $view = $useCase->execute(new PlaceOrderRequest([
            'isAuthenticated' => true
        ]));
    }
}
