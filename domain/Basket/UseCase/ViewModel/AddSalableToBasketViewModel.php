<?php

namespace Domain\Basket\UseCase\ViewModel;

use Domain\Sales\Entity\Salable;

class AddSalableToBasketViewModel
{
    public Salable $salable;

    public function __construct(Salable $salable)
    {
        $this->salable = $salable;
    }
}
