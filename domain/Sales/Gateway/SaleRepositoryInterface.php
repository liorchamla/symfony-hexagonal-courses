<?php

namespace Domain\Sales\Gateway;

use Domain\Authentication\Entity\User;
use Domain\Courses\Entity\Course;
use Domain\Sales\Entity\Sale;

interface SaleRepositoryInterface
{
    public function findOneOrNull(string $uuid): ?Sale;
    public function store(Sale $sale);
    public function findSalesForCourse(Course $course): iterable;
    public function findSalesForUser(User $user): iterable;
}
