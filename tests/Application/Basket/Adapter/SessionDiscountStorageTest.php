<?php

namespace App\Tests\Application\Basket\Adapter;

use App\Tests\Application\WithClientTrait;
use App\Basket\Adapter\SessionDiscountStorage;
use App\Tests\Domain\Sales\Factory\DiscountFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SessionDiscountStorageTest extends WebTestCase
{
    use WithClientTrait;

    protected SessionDiscountStorage $storage;

    protected function setUp(): void
    {
        $this->setUpClient();

        $this->storage = new SessionDiscountStorage($this->session);
    }

    /** @test */
    public function session_is_affected_by_discount_storage()
    {
        // Given we have a discount
        $discount = DiscountFactory::create();

        // When we add it to storage
        $this->storage->addDiscount($discount);

        // Then we find it in session
        $discounts = $this->session->get('basket.discounts', []);
        $this->assertCount(1, $discounts);
    }

    /** @test */
    public function we_can_retrieve_discounts_from_the_session_with_storage()
    {
        // Given we have several discounts in session
        $discounts = [
            DiscountFactory::create(),
            DiscountFactory::create(),
            DiscountFactory::create(),
        ];

        $this->session->set('basket.discounts', $discounts);

        // When we ask for discounts 
        $storedDiscounts = $this->storage->getDiscounts();

        // Then it contains session discounts
        $this->assertCount(3, $storedDiscounts);
    }
}
