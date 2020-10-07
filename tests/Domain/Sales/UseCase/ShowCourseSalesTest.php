<?php

namespace App\Tests\Domain\Sales\UseCase;

use App\Tests\Domain\Authentication\Factory\UserFactory;
use App\Tests\Domain\Courses\Adapters\InMemoryCourseRepository;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use App\Tests\Domain\Sales\Adapters\InMemorySaleRepository;
use App\Tests\Domain\Sales\Factory\SaleFactory;
use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Sales\UseCase\RequestModel\ShowCourseSalesRequest;
use Domain\Sales\UseCase\ShowCourseSales;
use PHPUnit\Framework\TestCase;

class ShowCourseSalesTest extends TestCase
{

    /**
     * 
     *
     * @test 
     */
    public function we_cant_see_courses_sales_for_an_unexisting_course()
    {
        // Setup
        $courseRepository = new InMemoryCourseRepository;
        $saleRepository = new InMemorySaleRepository;

        // Given we have no courses
        // When we execute the useCase
        // Then it should throw
        $this->expectException(CourseNotFoundException::class);
        $useCase = new ShowCourseSales($saleRepository, $courseRepository);
        $view = $useCase->execute(
            new ShowCourseSalesRequest(
                [
                'uuid' => 'unexisting-course-uuid'
                ]
            )
        );
    }

    /**
     * 
     *
     * @test 
     */
    public function we_can_see_courses_sales_for_an_existing_course()
    {
        // Setup
        $courseRepository = new InMemoryCourseRepository;
        $saleRepository = new InMemorySaleRepository;

        // Given we have a course
        $course = CourseFactory::create();
        $courseRepository->store($course);

        // And it was sold several times
        $sales = SaleFactory::createMany(
            20, [
            'items' => [$course]
            ]
        );

        foreach ($sales as $s) {
            $saleRepository->store($s);
        }

        // When we execute the useCase
        $useCase = new ShowCourseSales($saleRepository, $courseRepository);
        $view = $useCase->execute(
            new ShowCourseSalesRequest(
                [
                'uuid' => $course->uuid
                ]
            )
        );

        // Then we should see sales
        $this->assertEquals($course, $view->course);
        $this->assertCount(20, $view->sales);
    }
}
