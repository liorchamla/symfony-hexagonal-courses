<?php

namespace App\Basket\Adapter;

use Domain\Authentication\Entity\User;
use Domain\Basket\Gateway\BasketStorageInterface;
use Domain\Sales\Entity\Salable;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionBasketStorage implements BasketStorageInterface
{
    protected SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        if (false === $this->session->has('basket.items')) {
            $this->session->set('basket.items', []);
        }
    }

    public function setOwner(User $owner)
    {
        $this->session->set('basket.owner', $owner);
    }

    public function getOwner(): ?User
    {
        return $this->session->get('basket.owner', null);
    }

    public function addItem(Salable $item)
    {
        $basket = $this->getBasket();
        $basket[$item->getUuid()] = $item;
        $this->saveBasket($basket);
    }

    public function getItem(string $uuid): ?Salable
    {
        $basket = $this->getBasket();

        return $basket[$uuid] ?? null;
    }

    public function removeItem(Salable $item)
    {
        $basket = $this->getBasket();

        unset($basket[$item->getUuid()]);

        $this->saveBasket($basket);
    }

    public function getItems(): array
    {
        return $this->getBasket();
    }

    protected function getBasket(): array
    {
        return $this->session->get('basket.items', []);
    }

    protected function saveBasket(array $basket)
    {
        $this->session->set('basket.items', $basket);
    }
}
