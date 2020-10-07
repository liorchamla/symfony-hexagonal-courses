<?php

namespace App\Basket\Adapter;


use LogicException;
use Doctrine\ORM\Query\Expr;
use Domain\Basket\Entity\Discount;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Basket\DoctrineEntity\DiscountEntity;
use App\Courses\DoctrineEntity\ChapterEntity;
use App\Courses\DoctrineEntity\CourseEntity;
use Domain\Basket\Gateway\DiscountRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Domain\Courses\Entity\Course;

class DoctrineDiscountRepository implements ServiceEntityRepositoryInterface, DiscountRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry->getManagerForClass(DiscountEntity::class);
    }

    public function findOneOrNull(string $uuid): ?Discount
    {
        /** @var DiscountEntity */
        $discount = $this->em->createQueryBuilder()
            ->select('d')
            ->from(DiscountEntity::class, 'd')
            ->where('d.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $discount) {
            return null;
        }

        return DiscountEntity::toDomain($discount);
    }

    public function store(Discount $discount)
    {
        $discountEntity = DiscountEntity::fromDomain($discount);

        $discountEntity->items = $discountEntity->items->map(function (CourseEntity $c) {
            $courseEntity = $this->em->find(CourseEntity::class, $c->uuid);
            if (null !== $courseEntity) {
                return $courseEntity;
            }
        });
        $this->em->persist($discountEntity);
        $this->em->flush();
    }

    public function decrementDiscountMaximumUses(Discount $discount)
    {
        $discount->maximumUses--;

        $entity = $this->em->find(DiscountEntity::class, $discount->uuid);

        $entity->maximumUses--;
        $this->em->flush();

        // $this->em->createQueryBuilder()
        //     ->update(DiscountEntity::class, 'd')
        //     ->set('d.maximumUses', $discount->maximumUses)
        //     ->where('d.uuid = :uuid')
        //     ->setParameter('uuid', $discount->uuid)
        //     ->getQuery()
        //     ->execute();
    }
}
