<?php

namespace Domain\Courses\Entity;

use Domain\Sales\Entity\Salable;
use Ramsey\Uuid\Uuid;

class Course implements Salable
{
    public ?string $uuid = null;
    public ?string $title = '';
    public ?int $price = 0;

    public function __construct(string $title, int $price, ?string $uuid = null)
    {
        $this->title = $title;
        $this->price = $price;
        $this->uuid = $uuid ?? Uuid::uuid1();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
