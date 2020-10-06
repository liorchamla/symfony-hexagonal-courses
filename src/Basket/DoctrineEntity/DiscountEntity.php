<?php

namespace App\Basket\DoctrineEntity;

use Doctrine\ORM\Mapping as ORM;
use Domain\Basket\Entity\Discount;
use App\Courses\DoctrineEntity\CourseEntity;
use App\Basket\Adapter\DoctrineDiscountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Courses\Entity\Course;

/**
 * @ORM\Entity(repositoryClass=DoctrineDiscountRepository::class)
 * @ORM\Table(name="discount")
 */
class DiscountEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    public ?string $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $scope = Discount::SCOPE_GLOBAL;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $type = Discount::TYPE_PERCENT;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $value = 0;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $maximumUses = Discount::MAXIMUM_USES_INFINITY;

    /**
     * @ORM\ManyToMany(targetEntity="App\Courses\DoctrineEntity\CourseEntity")
     * @ORM\JoinTable(name="discount_course",
     *      joinColumns={@ORM\JoinColumn(name="discount_uuid", referencedColumnName="uuid")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="course_uuid", referencedColumnName="uuid")}
     *      )
     */
    public Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public static function toDomain(DiscountEntity $entity): Discount
    {
        return new Discount(
            $entity->scope,
            $entity->type,
            $entity->value,
            $entity->maximumUses,
            $entity->items->map(fn (CourseEntity $course) => CourseEntity::toDomain($course))->toArray(),
            $entity->uuid
        );
    }

    public static function fromDomain(Discount $discount): self
    {
        $entity = new self;

        $entity->scope = $discount->scope;
        $entity->type = $discount->type;
        $entity->items = (new ArrayCollection($discount->items))->map(fn (Course $course) => CourseEntity::fromDomain($course));
        $entity->maximumUses = $discount->maximumUses;
        $entity->value = $discount->value;
        $entity->uuid = $discount->uuid;

        return $entity;
    }
}
