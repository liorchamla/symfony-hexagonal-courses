<?php

namespace App\Tests\Application\Basket\Entity;

use PHPUnit\Framework\TestCase;
use Domain\Basket\Entity\Discount;
use App\Basket\DoctrineEntity\DiscountEntity;
use App\Courses\DoctrineEntity\CourseEntity;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use App\Tests\Domain\Sales\Factory\DiscountFactory;
use Doctrine\Common\Collections\ArrayCollection;

class DiscountEntityTest extends TestCase
{
    /**
     * 
     *
     * @test 
     */
    public function we_have_a_discount_entity()
    {
        $entity = new DiscountEntity;

        $this->assertEquals(Discount::SCOPE_GLOBAL, $entity->scope);
        $this->assertEquals(Discount::TYPE_PERCENT, $entity->type);
        $this->assertEquals(0, $entity->value);
        $this->assertEquals([], $entity->items->toArray());
        $this->assertEquals(Discount::MAXIMUM_USES_INFINITY, $entity->maximumUses);
    }

    /**
     * 
     *
     * @test 
     */
    public function we_can_transform_doctrine_entity_into_domain_entity()
    {
        $entity = new DiscountEntity;

        $entity->scope = Discount::SCOPE_SPECIFIC;
        $entity->type = Discount::TYPE_FIXED;
        $entity->items = new ArrayCollection(
            [
            CourseEntity::fromDomain(
                CourseFactory::create()
            )
            ]
        );
        $entity->uuid =  'fake-uuid';

        $discount = DiscountEntity::toDomain($entity);

        $this->assertInstanceOf(Discount::class, $discount);
    }

    /**
     * 
     *
     * @test 
     */
    public function we_can_transform_a_domain_discount_into_a_doctrine_entity()
    {
        $discount = DiscountFactory::create();

        $entity = DiscountEntity::fromDomain($discount);

        $this->assertInstanceOf(DiscountEntity::class, $entity);
    }
}
