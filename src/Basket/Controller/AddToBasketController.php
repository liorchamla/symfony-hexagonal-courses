<?php

namespace App\Basket\Controller;

use Domain\Basket\BasketManager;
use Domain\Basket\Exception\SalableCouldNotBeAddedToBasketException;
use Domain\Basket\UseCase\AddSalableToBasket;
use Domain\Basket\UseCase\RequestModel\AddSalableToBasketRequest;
use Domain\Courses\Entity\Course;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddToBasketController extends AbstractController
{

    protected BasketManager $manager;
    protected AddSalableToBasket $useCase;
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(AddSalableToBasket $useCase, BasketManager $basketManager, CourseRepositoryInterface $courseRepository)
    {
        $this->manager = $basketManager;
        $this->useCase = $useCase;
        $this->courseRepository = $courseRepository;
    }

    /**
     * @Route("/basket/add/{uuid}", name="add_to_basket")
     */
    public function __invoke(string $uuid)
    {
        $course = $this->courseRepository->findOneOrNull($uuid);

        try {
            $view = $this->useCase->execute(new AddSalableToBasketRequest([
                'salable_class' => Course::class,
                'salable_uuid' => $uuid
            ]));

            $this->addFlash('success', sprintf(
                'The salable with UUID %s was added to basket !',
                $uuid
            ));
        } catch (SalableCouldNotBeAddedToBasketException $e) {
            $this->addFlash('danger', sprintf(
                'The salable with UUID %s was found and was not added to basket',
                $uuid
            ));
        }

        return new RedirectResponse('/');
    }
}
