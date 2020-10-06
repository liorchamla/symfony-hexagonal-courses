<?php

namespace Domain\Sales\UseCase\ViewModel;

use Domain\Authentication\Entity\User;

class ShowUserSalesViewModel
{
    public User $user;
    public iterable $sales;

    public function __construct(User $user, iterable $sales)
    {
        $this->user = $user;
        $this->sales = $sales;
    }
}
