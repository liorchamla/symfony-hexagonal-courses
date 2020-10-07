<?php

namespace App\Tests\Application\Courses\Controller;

use App\Tests\Application\Courses\Adapter\MockSecurity;
use App\Tests\Application\Courses\Adapter\MockUserInterface;
use App\Tests\Application\Courses\Adapter\MockUserRepository;
use App\Tests\Application\WithClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Domain\Courses\Gateway\ChapterRepositoryInterface;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Symfony\Component\Security\Core\Security;

class ShowChapterControllerTest extends WebTestCase
{

    use WithClientTrait;

    protected CourseRepositoryInterface $courseRepository;
    protected ChapterRepositoryInterface $chapterRepository;

    protected function setUp(): void
    {
        $this->setUpClient();

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
    public function unauthenticated_user_can_see_chapter_but_not_play()
    {
        // Given we have a course
        $course = $this->createCourse();

        // And it has chapters
        $chapter = $this->createChapter(['course' => $course]);

        $this->replaceServiceInContainer('Domain\Authentication\Gateway\UserRepositoryInterface', new MockUserRepository());

        // When we show a chapter
        $this->get(
            'show_chapter', [
            'course_uuid' => $course->uuid,
            'chapter_uuid' => $chapter->uuid
            ]
        );

        // Then we can see the chapter
        $this->assertResponseStatusCodeSame(200);

        // And we can't see the player
        $this->assertSelectorNotExists('video');
    }

    /**
     * 
     *
     * @test 
     */
    public function an_authenticated_user_who_has_not_bought_the_course_cant_see_the_player()
    {
        // Given we have a course
        $course = $this->createCourse();

        // And it has chapters
        $chapter = $this->createChapter(['course' => $course]);

        $this->replaceServiceInContainer('Domain\Authentication\Gateway\UserRepositoryInterface', new MockUserRepository(true));

        // When we show a chapter
        $this->get(
            'show_chapter', [
            'course_uuid' => $course->uuid,
            'chapter_uuid' => $chapter->uuid
            ]
        );

        // Then we can see the chapter
        $this->assertResponseStatusCodeSame(200);

        // And we can't see the player
        $this->assertSelectorNotExists('video');
    }

    /**
     * 
     *
     * @test 
     */
    public function an_authenticated_user_who_bought_the_course_will_see_player()
    {
        // Given we have a course
        $course = $this->createCourse();

        // And it has chapters
        $chapter = $this->createChapter(['course' => $course]);

        $this->replaceServiceInContainer('Domain\Authentication\Gateway\UserRepositoryInterface', new MockUserRepository(true, true));

        $this->replaceServiceInContainer(
            Security::class, new MockSecurity(
                new MockUserInterface
            )
        );

        // When we show a chapter
        $this->get(
            'show_chapter', [
            'course_uuid' => $course->uuid,
            'chapter_uuid' => $chapter->uuid
            ]
        );

        // Then we can see the chapter
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('video');
    }
}
