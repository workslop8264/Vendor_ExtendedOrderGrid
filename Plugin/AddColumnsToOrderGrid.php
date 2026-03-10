<?php

namespace Vendor\ExtendedOrderGrid\Plugin;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Vendor\ExtendedOrderGrid\Model\Field\FieldPool;

class AddColumnsToOrderGrid
{
    public function __construct(
        protected FieldPool $fieldPool
    ) { }

    public function afterGetData(DataProvider $subject, $result)
    {
        if ('sales_order_grid_data_source' !== $subject->getName()) return $result;
        if (!isset($result['items'])) return $result;

        foreach ($result['items'] as &$item) {
            foreach ($this->fieldPool->getFields() as $field) {
                $item[$field->getCode()] = $field->calculate($item);
            }
        }

        return $result;
    }
}
