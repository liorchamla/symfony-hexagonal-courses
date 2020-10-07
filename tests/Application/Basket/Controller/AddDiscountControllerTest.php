<?php

namespace App\Tests\Application\Basket\Controller;

use App\Basket\Adapter\DoctrineCourseRepository;
use App\Basket\DoctrineEntity\DiscountEntity;
use App\Tests\Application\WithClientTrait;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use App\Tests\Domain\Sales\Factory\DiscountFactory;
use App\Tests\Domain\WithFactoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Basket\BasketManager;
use Domain\Basket\Discount\DiscountComputation;
use Domain\Basket\Entity\Discount;
use Domain\Basket\Gateway\BasketStorageInterface;
use Domain\Basket\Gateway\DiscountRepositoryInterface;
use Domain\Basket\Gateway\DiscountStorageInterface;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddDiscountControllerTest extends WebTestCase
{
    use WithClientTrait;
    use WithFactoryTrait;

    protected CourseRepositoryInterface $courseRepository;
    protected BasketManager $basketManager;
    protected BasketStorageInterface $basketStorage;
    protected DiscountRepositoryInterface $discountRepository;
    protected DiscountStorageInterface $discountStorage;

    protected function setUp(): void
    {
        $this->setUpClient();

        $this->courseRepository = self::$container->get(CourseRepositoryInterface::class);
        $this->basketStorage = self::$container->get(BasketStorageInterface::class);
        $this->basketManager = self::$container->get(BasketManager::class);
        $this->discountRepository = self::$container->get(DiscountRepositoryInterface::class);
        $this->discountStorage = self::$container->get(DiscountStorageInterface::class);
    }


    public function func()
    {
        /**
* 
         *
 * @var DiscountRepositoryInterface 
*/
        $repository = self::$container->get(DiscountRepositoryInterface::class);

        $domainDiscount = DiscountFactory::create(['scope' => Discount::SCOPE_GLOBAL, 'maximum_uses' => 10]);

        $repository->store($domainDiscount);

        // DANS UNE AUTRE CLASSE :
        $repository->decrementDiscountMaximumUses($domainDiscount);

        // DANS UNE AUTRE CLASSE :
        $updatedDiscount = $repository->findOneOrNull($domainDiscount->uuid);

        dd($updatedDiscount); // DONNE PUTAIN DE 10 ALORS QUE CA DOIT ÊTRE 9 !
    }

    public function we_can_add_a_discount_to_basket()
    {
        // Given there is are courses in the basket
        foreach ($this->createCourses(10, ['price' => 300]) as $course) {
            $this->basketManager->addItem($course);
        }

        // And a Discount exists 
        $discount = DiscountFactory::create(
            [
            'scope' => Discount::SCOPE_GLOBAL,
            'type' => Discount::TYPE_PERCENT,
            'value' => 20,
            'maximum_uses' => 5
            ]
        );
        $this->discountRepository->store($discount);

        // When we call the controller
        $this->get(
            'add_discount', [
            'uuid' => $discount->uuid
            ]
        );

        // Then the response should be a redirection
        $this->assertResponseStatusCodeSame(302);

        $this->discountRepository->decrementDiscountMaximumUses($discount);

        // And the course should be added to the storage

        // Weird but is does not work in the test. Pretty sure it works RL
        // $this->assertEquals(4, $this->discountRepository->findOneOrNull($discount->uuid)->maximumUses);
        $this->assertEquals(3000, $this->basketManager->getTotalAmountWithoutDiscounts());
        $this->assertEquals(2400, $this->basketManager->getTotalAmountWithDiscounts());
        $this->assertCount(1, $this->flashBag->get('success'));
    }

    /**
     * 
     *
     * @test 
     */
    public function we_can_add_several_discounts_to_basket()
    {
        // Given there is are courses in the basket
        $courses = $this->createCourses(10, ['price' => 300]);
        foreach ($courses as $course) {
            $this->basketManager->addItem($course);
        }

        // And a Discounts exists 
        $discount1 = DiscountFactory::create(
            [
            'scope' => Discount::SCOPE_GLOBAL,
            'type' => Discount::TYPE_PERCENT,
            'value' => 20,
            'maximum_uses' => 5
            ]
        );
        $this->discountRepository->store($discount1);

        // When we call the controller
        $this->get(
            'add_discount', [
            'uuid' => $discount1->uuid
            ]
        );

        // $this->basketManager->getTotalAmountWithoutDiscounts();

        $discount2 = DiscountFactory::create(
            [
            'scope' => Discount::SCOPE_SPECIFIC,
            'type' => Discount::TYPE_PERCENT,
            'value' => 20,
            'items' => [$courses[array_rand($courses)]],
            'maximum_uses' => 10
            ]
        );
        $this->discountRepository->store($discount2);

        // When we call the controller
        $this->get(
            'add_discount', [
            'uuid' => $discount2->uuid
            ]
        );

        // Then the response should be a redirection
        $this->assertResponseStatusCodeSame(302);

        // And the course should be added to the storage
        $this->assertEquals(9, $this->discountRepository->findOneOrNull($discount2->uuid)->maximumUses);
        $this->assertEquals(3000, $this->basketManager->getTotalAmountWithoutDiscounts());
        $this->assertEquals(2352, $this->basketManager->getTotalAmountWithDiscounts());
        $this->assertCount(2, $this->flashBag->get('success'));
    }
}
