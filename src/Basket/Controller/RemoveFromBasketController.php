<?php

namespace App\Basket\Controller;

use Domain\Basket\BasketManager;
use Domain\Basket\Exception\SalableNotFoundInBasketException;
use Domain\Basket\UseCase\AddSalableToBasket;
use Domain\Basket\UseCase\RemoveSalableFromBasket;
use Domain\Basket\UseCase\RequestModel\AddSalableToBasketRequest;
use Domain\Basket\UseCase\RequestModel\RemoveSalableFromBasketRequest;
use Domain\Courses\Entity\Course;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RemoveFromBasketController extends AbstractController
{

    protected BasketManager $manager;
    protected RemoveSalableFromBasket $useCase;
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(RemoveSalableFromBasket $useCase, BasketManager $basketManager, CourseRepositoryInterface $courseRepository)
    {
        $this->manager = $basketManager;
        $this->useCase = $useCase;
        $this->courseRepository = $courseRepository;
    }

    /**
     * @Route("/basket/remove/{uuid}", name="remove_from_basket")
     */
    public function __invoke(string $uuid)
    {
        try {
            $this->useCase->execute(new RemoveSalableFromBasketRequest([
                'salable_uuid' => $uuid
            ]));

            $this->addFlash('success', sprintf(
                'Salable with UUID %s was removed from basket',
                $uuid
            ));
        } catch (SalableNotFoundInBasketException $e) {
            $this->addFlash('danger', sprintf(
                'Salable with UUID %s was not found in basket',
                $uuid
            ));
        }

        return new RedirectResponse('/');
    }
}
