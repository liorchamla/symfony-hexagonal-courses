<?php

namespace App\Basket\Controller;

use Domain\Basket\UseCase\AddDiscount;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Domain\Basket\Gateway\DiscountRepositoryInterface;
use Domain\Basket\UseCase\RequestModel\AddDiscountRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AddDiscountController extends AbstractController
{
    protected DiscountRepositoryInterface $discountRepository;
    protected AddDiscount $useCase;

    public function __construct(DiscountRepositoryInterface $discountRepository, AddDiscount $useCase)
    {
        $this->discountRepository = $discountRepository;
        $this->useCase = $useCase;
    }

    /**
     * @Route("/basket/discounts/add/{uuid}", name="add_discount")
     *
     * @return Response
     */
    public function __invoke(string $uuid): Response
    {
        $view = $this->useCase->execute(new AddDiscountRequest([
            'uuid' => $uuid
        ]));

        $this->addFlash('success', sprintf(
            'The discount with UUID %s was added !',
            $uuid
        ));

        return new RedirectResponse('/');
    }
}
