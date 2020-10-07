<?php

namespace App\Tests\Domain\Sales\UseCase;

use App\Tests\Domain\Authentication\Adapters\InMemoryUserRepository;
use App\Tests\Domain\Authentication\Factory\UserFactory;
use App\Tests\Domain\Sales\Adapters\InMemorySaleRepository;
use App\Tests\Domain\Sales\Factory\SaleFactory;
use Domain\Sales\UseCase\RequestModel\ShowUserSalesRequest;
use Domain\Sales\UseCase\ShowUserSales;
use PHPUnit\Framework\TestCase;

class ShowUserSalesTest extends TestCase
{
    /**
     * 
     *
     * @test 
     */
    public function we_can_see_users_sales()
    {
        // Setup
        $saleRepository = new InMemorySaleRepository;
        $userRepository = new InMemoryUserRepository;

        // Given we have a user
        $user = UserFactory::create();
        $userRepository->store($user);

        // And he did several orders 
        $sales = SaleFactory::createMany(10, ['owner' => $user]);
        foreach ($sales as $s) {
            $saleRepository->store($s);
        }

        // When we execute the use case
        $useCase = new ShowUserSales($saleRepository, $userRepository);
        $view = $useCase->execute(
            new ShowUserSalesRequest(
                [
                'uuid' => $user->uuid
                ]
            )
        );

        $this->assertCount(10, $view->sales);
    }
}
