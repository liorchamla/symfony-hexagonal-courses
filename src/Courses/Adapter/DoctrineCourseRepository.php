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
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry->getManagerForClass(CourseEntity::class);
    }

    public function findOneOrNull(string $uuid): ?Course
    {
        /** @var CourseEntity */
        $course = $this->em->createQueryBuilder()
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
    public function store(Course $course)
    {
        $courseEntity = CourseEntity::fromDomain($course);
        $this->em->persist($courseEntity);
        $this->em->flush();
    }

    public function delete(Course $course)
    {
        ///
    }
}
