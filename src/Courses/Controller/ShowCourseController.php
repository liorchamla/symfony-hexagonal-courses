<?php

namespace App\Courses\Controller;

use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\UseCase\RequestModel\ShowCourseRequest;
use Domain\Courses\UseCase\ShowCourse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowCourseController extends AbstractController
{
    protected ShowCourse $useCase;

    public function __construct(ShowCourse $useCase)
    {
        $this->useCase = $useCase;
    }

    /**
     * @Route("/courses/{uuid}", name="show_course")
     */
    public function __invoke(string $uuid): Response
    {
        $request = new ShowCourseRequest([
            'uuid' => $uuid
        ]);

        try {
            $view = $this->useCase->execute($request);

            return $this->render('Courses/show-course.twig', [
                'view' => $view
            ]);
        } catch (CourseNotFoundException $exception) {
            throw $this->createNotFoundException();
        }
    }
}
