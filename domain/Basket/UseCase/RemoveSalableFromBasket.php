<?php

namespace Domain\Basket\UseCase;

use Domain\Basket\BasketManager;
use Domain\Basket\Gateway\BasketStorageInterface;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Domain\Basket\Exception\SalableNotFoundInBasketException;
use Domain\Basket\UseCase\ViewModel\AddSalableToBasketViewModel;
use Domain\Basket\UseCase\RequestModel\AddSalableToBasketRequest;
use Domain\Basket\Exception\SalableCouldNotBeAddedToBasketException;
use Domain\Basket\UseCase\ViewModel\RemoveSalableFromBasketViewModel;
use Domain\Basket\UseCase\RequestModel\RemoveSalableFromBasketRequest;

class RemoveSalableFromBasket
{
    protected BasketManager $basketManager;

    public function __construct(BasketManager $basketManager)
    {
        $this->basketManager = $basketManager;
    }

    /**
     * @param RemoveSalableFromBasketRequest $request
     *
     * @return RemoveSalableFromBasketViewModel
     *
     * @throws SalableNotFoundInBasketException
     */
    public function execute(RemoveSalableFromBasketRequest $request): RemoveSalableFromBasketViewModel
    {
        $removedUuid = $request->salable_uuid;

        $salable = $this->basketManager->getItem($removedUuid);

        if (null === $salable) {
            throw new SalableNotFoundInBasketException(
                sprintf(
                    'Salable with UUID %s was not found in the basket and cant be removed !',
                    $removedUuid
                )
            );
        }

        $this->basketManager->removeItem($salable);

        return new RemoveSalableFromBasketViewModel($salable);
    }
}
