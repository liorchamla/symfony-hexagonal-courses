<?php

namespace App\Basket\Adapter;

use Domain\Basket\Entity\Discount;
use Domain\Basket\Gateway\DiscountStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionDiscountStorage implements DiscountStorageInterface
{
    protected SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        if (false === $this->session->has('basket.discounts')) {
            $this->session->set('basket.discounts', []);
        }
    }

    public function addDiscount(Discount $discount)
    {
        $discounts = $this->getDiscounts();
        $discounts[$discount->uuid] = $discount;
        $this->saveDiscounts($discounts);
    }

    public function getDiscounts(): array
    {
        return $this->session->get('basket.discounts', []);
    }

    protected function saveDiscounts(array $discounts)
    {
        $this->session->set('basket.discounts', $discounts);
    }
}
