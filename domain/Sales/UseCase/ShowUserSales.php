<?php

namespace Domain\Sales\UseCase;

use Domain\Authentication\Gateway\UserRepositoryInterface;
use Domain\Sales\Gateway\SaleRepositoryInterface;
use Domain\Sales\UseCase\RequestModel\ShowUserSalesRequest;
use Domain\Sales\UseCase\ViewModel\ShowUserSalesViewModel;

class ShowUserSales
{
    protected SaleRepositoryInterface $saleRepository;
    protected UserRepositoryInterface $userRepository;

    public function __construct(SaleRepositoryInterface $saleRepository, UserRepositoryInterface $userRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(ShowUserSalesRequest $request): ShowUserSalesViewModel
    {
        $user = $this->userRepository->findOneOrNull($request->uuid);

        $sales = $this->saleRepository->findSalesForUser($user);

        return new ShowUserSalesViewModel($user, $sales);
    }
}
