<?php

namespace Domain\Sales\Entity;

interface Salable
{
    public function getUuid(): string;
    public function getPrice(): int;
}
