<?php

namespace Vendor\ExtendedOrderGrid\Model\Field;

interface FieldInterface
{
    /**
     * The code (internal key) for the field.
     */
    public function getCode(): string;

    /**
     * Calculate the value for the field based on the order row data.
     *
     * @param array $orderRow The order row data from the UI component
     * @return mixed The calculated value for the field
     */
    public function calculate(array $orderRow): mixed;

    /**
     * Whether the field is sortable (argument "sortable")
     */
    public function isSortable(): bool;

    /**
     * Extra arguments for the field.
     */
    public function getArguments(): array;

    /**
     * Label for the field.
     */
    public function getLabel(): string;

    /**
     * Renderer class for the field.
     */
    public function getColumnClass(): ?string;
}
