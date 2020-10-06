<?php

namespace Domain\Basket\UseCase;

use Domain\Basket\BasketManager;
use Domain\Basket\Exception\SalableCouldNotBeAddedToBasketException;
use Domain\Basket\Gateway\BasketStorageInterface;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Domain\Basket\UseCase\ViewModel\AddSalableToBasketViewModel;
use Domain\Basket\UseCase\RequestModel\AddSalableToBasketRequest;

class AddSalableToBasket
{
    protected BasketManager $basketManager;
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(BasketManager $basketManager, CourseRepositoryInterface $courseRepository)
    {
        $this->basketManager = $basketManager;
        $this->courseRepository = $courseRepository;
    }

    public function execute(AddSalableToBasketRequest $request): AddSalableToBasketViewModel
    {
        $course = $this->courseRepository->findOneOrNull($request->salable_uuid);

        if (null === $course) {
            throw new SalableCouldNotBeAddedToBasketException(sprintf(
                'Salable with UUID %s was not found !',
                $request->salable_uuid
            ));
        }

        $this->basketManager->addItem($course);

        return new AddSalableToBasketViewModel($course);
    }
}
