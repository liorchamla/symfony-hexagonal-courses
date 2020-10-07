<?php

namespace App\Tests\Application\Basket\Adapter;

use App\Basket\Adapter\DoctrineDiscountRepository;
use App\Tests\Application\WithClientTrait;
use App\Tests\Domain\Sales\Factory\DiscountFactory;
use Domain\Basket\Entity\Discount;
use Domain\Basket\Gateway\DiscountRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DoctrineDiscountRepositoryTest extends WebTestCase
{
    use WithClientTrait;

    protected function setUp(): void
    {
        $this->setUpClient();
    }

    /**
     * 
     *
     * @test 
     */
    public function it_can_store_and_retrieve_a_discount()
    {
        /**
* 
         *
 * @var DiscountRepositoryInterface 
*/
        $discountRepository = self::$container->get(DoctrineDiscountRepository::class);

        $discount = DiscountFactory::create(
            [
            'scope' => Discount::SCOPE_GLOBAL
            ]
        );

        $discountRepository->store($discount);

        $storedDiscount = $discountRepository->findOneOrNull($discount->uuid);


        $this->assertInstanceOf(Discount::class, $storedDiscount);
        $this->assertEquals($discount->uuid, $storedDiscount->uuid);
    }

    /**
     * 
     *
     * @test 
     */
    public function it_can_store_and_retrieve_a_specific_discount_with_courses()
    {
        /**
* 
         *
 * @var DoctrineDiscountRepository 
*/
        $discountRepository = self::$container->get(DoctrineDiscountRepository::class);

        $courses = $this->createCourses(3, ['price' => 300]);

        $discount = DiscountFactory::create(
            [
            'scope' => Discount::SCOPE_SPECIFIC,
            'items' => $courses
            ]
        );

        $discountRepository->store($discount);

        $storedDiscount = $discountRepository->findOneOrNull($discount->uuid);

        $this->assertInstanceOf(Discount::class, $storedDiscount);
        $this->assertEquals($discount->uuid, $storedDiscount->uuid);
        $this->assertCount(3, $storedDiscount->items);
    }
}
