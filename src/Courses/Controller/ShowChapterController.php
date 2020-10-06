<?php

namespace App\Courses\Controller;

use Domain\Courses\UseCase\RequestModel\ShowChapterRequest;
use Domain\Courses\UseCase\ShowChapter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class ShowChapterController extends AbstractController
{
    protected ShowChapter $useCase;
    protected Security $security;

    public function __construct(ShowChapter $useCase, Security $security)
    {
        $this->useCase = $useCase;
        $this->security = $security;
    }

    /**
     * @Route("/course/{course_uuid}/{chapter_uuid}", name="show_chapter")
     */
    public function __invoke(string $course_uuid, string $chapter_uuid): Response
    {
        $view = $this->useCase->execute(new ShowChapterRequest([
            'uuid' => $chapter_uuid,
            'userUuid' => $this->security->getUser() ? $this->security->getUser()->getUuid() : null
        ]));

        return $this->render('Courses/show-chapter.twig', [
            'view' => $view
        ]);
    }
}
