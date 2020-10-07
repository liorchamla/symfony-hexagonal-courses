<?php

namespace App\Courses\Adapter;



use Domain\Courses\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Courses\DoctrineEntity\CourseEntity;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;


class DoctrineCourseRepository implements ServiceEntityRepositoryInterface, CourseRepositoryInterface
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry)
    {
        $this->manager = $registry->getManagerForClass(CourseEntity::class);
    }

    public function findOneOrNull(string $uuid): ?Course
    {
        /** @var CourseEntity */
        $course = $this->manager->createQueryBuilder()
            ->select('c')
            ->from(CourseEntity::class, 'c')
            ->andWhere('c.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $course) {
            return null;
        }

        return CourseEntity::toDomain($course);
    }
    public function store(Course $course): void
    {
        $courseEntity = CourseEntity::fromDomain($course);
        $this->manager->persist($courseEntity);
        $this->manager->flush();
    }

    public function delete(Course $course): void
    {
        ///
    }

    public function update(Course $course): void
    {
        ///
    }
}
