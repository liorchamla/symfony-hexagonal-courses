<?php

namespace App\Courses\DoctrineEntity;

use App\Courses\Adapter\DoctrineCourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Domain\Courses\Entity\Course;

/**
 * @ORM\Entity(repositoryClass=DoctrineCourseRepository::class)
 * @ORM\Table(name="course")
 */
class CourseEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    public ?string $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public ?string $title;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Courses\DoctrineEntity\ChapterEntity", mappedBy="course", cascade={"persist"}, orphanRemoval=true)
     */
    public Collection $chapters;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
    }



    public static function toDomain(CourseEntity $entity): Course
    {
        $domainCourse = new Course($entity->title, $entity->price);
        $domainCourse->uuid = $entity->uuid;

        return $domainCourse;
    }

    public static function fromDomain(Course $course): self
    {
        $entity = new self;
        $entity->setPrice($course->price)
            ->setTitle($course->title)
            ->setUuid($course->uuid);

        return $entity;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
