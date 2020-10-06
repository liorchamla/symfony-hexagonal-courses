<?php

namespace App\Courses\Adapter;

use Doctrine\ORM\Query\Expr;
use Domain\Courses\Entity\Course;
use Domain\Courses\Entity\Chapter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Courses\DoctrineEntity\CourseEntity;
use App\Courses\DoctrineEntity\ChapterEntity;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Domain\Courses\Gateway\ChapterRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;


class DoctrineChapterRepository implements ServiceEntityRepositoryInterface, ChapterRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry->getManagerForClass(ChapterEntity::class);
    }

    public function findOneOrNull(string $uuid): ?Chapter
    {
        /** @var ChapterEntity */
        $chapter = $this->em->createQueryBuilder()
            ->select('c')
            ->from(ChapterEntity::class, 'c')
            ->andWhere('c.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $chapter) {
            return null;
        }

        return ChapterEntity::toDomain($chapter);
    }

    public function store(Chapter $chapter)
    {
        $chapterEntity = ChapterEntity::fromDomain($chapter);
        $courseEntity = $this->em->find(CourseEntity::class, $chapterEntity->course->uuid);

        if ($courseEntity) {
            $chapterEntity->course = $courseEntity;
        }

        $this->em->persist($chapterEntity);


        $this->em->flush();
    }


    public function findChaptersForCourse(string $courseUuid): array
    {
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from(ChapterEntity::class, 'c')
            ->innerJoin(CourseEntity::class, 'cc', Expr\Join::WITH, 'c.courseUuid = cc.uuid')
            ->where('cc.uuid = :uuid')
            ->setParameter('uuid', $courseUuid)
            ->getQuery()
            ->getResult();
    }

    public function delete(Chapter $chapter)
    {
        //
    }
}
