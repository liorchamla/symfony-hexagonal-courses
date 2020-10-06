<?php

namespace Domain\Basket\UseCase;

use Domain\Basket\BasketManager;
use Domain\Basket\Discount\DiscountComputation;
use Domain\Sales\Exception\DiscountNotFoundException;
use Domain\Basket\Gateway\DiscountRepositoryInterface;
use Domain\Basket\UseCase\ViewModel\AddDiscountViewModel;
use Domain\Basket\UseCase\RequestModel\AddDiscountRequest;


class AddDiscount
{
    protected DiscountRepositoryInterface $discountRepository;
    protected DiscountComputation $computation;
    protected BasketManager $basketManager;

    public function __construct(DiscountRepositoryInterface $discountRepository, BasketManager $basketManager)
    {
        $this->discountRepository = $discountRepository;
        $this->basketManager = $basketManager;
    }

    public function execute(AddDiscountRequest $request): AddDiscountViewModel
    {
        $discount = $this->discountRepository->findOneOrNull($request->uuid);

        if (null === $discount) {
            throw new DiscountNotFoundException(sprintf(
                'No discount with UUID %s was found !',
                $request->uuid
            ));
        }

        $this->basketManager->applyDiscount($discount);

        $this->discountRepository->decrementDiscountMaximumUses($discount);

        return new AddDiscountViewModel;
    }
}
