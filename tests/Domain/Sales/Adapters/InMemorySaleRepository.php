<?php

namespace App\Tests\Domain\Sales\Adapters;

use Domain\Authentication\Entity\User;
use Domain\Courses\Entity\Course;
use Domain\Sales\Entity\Sale;
use Domain\Sales\Gateway\SaleRepositoryInterface;

class InMemorySaleRepository implements SaleRepositoryInterface
{

    /**
     * @var Sale[]
     */
    public array $sales = [];

    public function store(Sale $sale)
    {
        $this->sales[$sale->uuid] = $sale;
    }

    public function findOneOrNull(string $uuid): ?Sale
    {
        return $this->sales[$uuid] ?? null;
    }

    public function findSalesForCourse(Course $course): iterable
    {
        return array_filter($this->sales, function (Sale $s) use ($course) {
            return in_array($course, $s->items) !== false;
        });
    }

    public function findSalesForUser(User $user): iterable
    {
        return array_filter($this->sales, fn (Sale $sale) => $sale->buyer === $user);
    }
}
