<?php

namespace App\Courses\DoctrineEntity;

use App\Courses\Adapter\DoctrineChapterRepository;
use Doctrine\ORM\Mapping as ORM;
use Domain\Courses\Entity\Chapter;
use Domain\Courses\Entity\Course;

/**
 * @ORM\Entity(repositoryClass=DoctrineChapterRepository::class)
 * @ORM\Table(name="chapter")
 */
class ChapterEntity
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     */
    public ?string $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $courseUuid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Courses\DoctrineEntity\CourseEntity", inversedBy="chapters", cascade={"persist"})
     * @ORM\JoinColumn(name="course_uuid", referencedColumnName="uuid")
     */
    public CourseEntity $course;

    public static function fromDomain(Chapter $chapter): self
    {
        $entity = new self;

        $entity->courseUuid = $chapter->course->uuid;
        $entity->course = CourseEntity::fromDomain($chapter->course);
        $entity->title = $chapter->title;
        $entity->uuid = $chapter->uuid;

        return $entity;
    }

    public static function toDomain(ChapterEntity $chapterEntity): Chapter
    {
        return new Chapter(
            CourseEntity::toDomain($chapterEntity->course),
            $chapterEntity->title
        );
    }
}
