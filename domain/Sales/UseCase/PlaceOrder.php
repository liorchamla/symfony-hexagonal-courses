<?php

namespace Domain\Sales\UseCase;

use Domain\Sales\Entity\Sale;
use Domain\Basket\BasketManager;
use Domain\Sales\Entity\Salable;
use Domain\Courses\Entity\Course;
use Domain\Sales\Gateway\SaleRepositoryInterface;
use Domain\Sales\UseCase\ViewModel\PlaceOrderViewModel;
use Domain\Sales\UseCase\RequestModel\PlaceOrderRequest;
use Domain\Sales\Exception\OrderCouldNotBePlacedWithEmptyBasketException;
use Domain\Sales\Exception\OrderCouldNotBePlacedWithNoAuthenticatedUserException;

class PlaceOrder
{
    protected BasketManager $basketManager;
    protected SaleRepositoryInterface $saleRepository;

    public function __construct(BasketManager $basketManager, SaleRepositoryInterface $saleRepository)
    {
        $this->basketManager = $basketManager;
        $this->saleRepository = $saleRepository;
    }

    public function execute(PlaceOrderRequest $request): PlaceOrderViewModel
    {
        if (false === $request->isAuthenticated) {
            throw new OrderCouldNotBePlacedWithNoAuthenticatedUserException(
                sprintf(
                    'Order could not be placed because the current user is not authenticated'
                )
            );
        }

        if (0 === count($this->basketManager->getItems())) {
            throw new OrderCouldNotBePlacedWithEmptyBasketException(
                sprintf(
                    'Order could not be placed because the basket is empty'
                )
            );
        }

        $sale = Sale::createFromBasket($this->basketManager);

        $this->saleRepository->store($sale);

        $this->linkCoursesToUser();

        return new PlaceOrderViewModel($sale);
    }

    protected function linkCoursesToUser()
    {
        $courses = array_filter($this->basketManager->getItems(), fn (Salable $item) => get_class($item) === Course::class);

        array_walk($courses, fn (Course $c) => $this->basketManager->getOwner()->addCourse($c));
    }
}
