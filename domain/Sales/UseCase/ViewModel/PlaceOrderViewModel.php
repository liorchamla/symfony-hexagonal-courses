<?php

namespace Domain\Sales\UseCase\ViewModel;

use Domain\Sales\Entity\Sale;

class PlaceOrderViewModel
{
    public Sale $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }
}
