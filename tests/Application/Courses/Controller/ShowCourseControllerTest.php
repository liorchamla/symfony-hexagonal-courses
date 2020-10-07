<?php

namespace App\Tests\Application\Courses\Controller;

use App\Tests\Application\WithClientTrait;
use Domain\Courses\Gateway\ChapterRepositoryInterface;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowCourseControllerTest extends WebTestCase
{

    use WithClientTrait;

    protected CourseRepositoryInterface $courseRepository;
    protected ChapterRepositoryInterface $chapterRepository;

    protected function setUp(): void
    {
        $this->setUpClient();

        // Setup
        /**
* 
         *
 * @var CourseRepositoryInterface 
*/
        $this->courseRepository = self::$container->get(CourseRepositoryInterface::class);
        /**
* 
         *
 * @var ChapterRepositoryInterface 
*/
        $this->chapterRepository = self::$container->get(ChapterRepositoryInterface::class);
    }

    /**
     * 
     *
     * @test 
     */
    public function it_should_show_a_course_and_its_chapters()
    {
        // Given there is a course
        $course = $this->createCourse();

        // And chapters on this course
        $chapter1 = $this->createChapter(['course' => $course]);
        $chapter2 = $this->createChapter(['course' => $course]);

        // When we call the controller
        $this->get('show_course', ['uuid' => $course->uuid]);

        // Then the response should ...
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', $course->title);
        $this->assertEquals(2, $this->client->getCrawler()->filter('li')->count());
    }

    /**
     * 
     *
     * @test 
     */
    public function it_should_display_a_404_if_course_is_not_found()
    {
        // When we call the controller
        $this->get('show_course', ['uuid' => 'unexisting-course-uuid']);

        // Then the response should ...
        $this->assertResponseStatusCodeSame(404);
    }
}
