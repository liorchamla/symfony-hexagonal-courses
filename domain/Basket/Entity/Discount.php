<?php

namespace Domain\Basket\Entity;

use Domain\Sales\Exception\IncoherentDiscountConfigurationException;
use Ramsey\Uuid\Uuid;

class Discount
{
    public const SCOPE_GLOBAL = 'scope.global';
    public const SCOPE_SPECIFIC = 'scope.specific';
    public const TYPE_FIXED = 'type.fixed';
    public const TYPE_PERCENT = 'type.percent';
    public const MAXIMUM_USES_INFINITY = 9999999;

    public string $uuid;
    public string $scope;
    public string $type;
    public int $value;
    public int $maximumUses;

    /** @var Salable[] */
    public array $items = [];

    public function __construct(string $scope = self::SCOPE_GLOBAL, string $type = self::TYPE_FIXED, int $value, int $maximumUses, array $items = [], ?string $uuid = '')
    {
        $this->scope = $scope;
        $this->type = $type;
        $this->value = $value;
        $this->maximumUses = $maximumUses;
        $this->items = $items;

        if ($this->scope === self::SCOPE_SPECIFIC && 0 === count($this->items)) {
            throw new IncoherentDiscountConfigurationException(sprintf(
                'Discount with scope "%s" could not be created. You passed 0 items',
                self::SCOPE_SPECIFIC
            ));
        }
        $this->uuid = $uuid;

        if (!$this->uuid) {
            $this->uuid = Uuid::uuid1();
        }
    }
}
