<?php

namespace Vendor\ExtendedOrderGrid\Model\Field;

class FieldPool
{
    public function __construct(
        protected array $fields = []
    ) { }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function get(string $code): ?FieldInterface
    {
        return $this->fields[$code] ?? null;
    }
}
