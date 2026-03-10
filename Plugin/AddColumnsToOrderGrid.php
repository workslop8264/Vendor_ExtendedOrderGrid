<?php

namespace Vendor\ExtendedOrderGrid\Plugin;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Vendor\ExtendedOrderGrid\Model\Field\FieldInterface;
use Vendor\ExtendedOrderGrid\Model\Field\FieldPool;

class AddColumnsToOrderGrid
{
    public function __construct(
        protected FieldPool $fieldPool
    ) { }

    public function afterGetMeta(DataProvider $subject, $result)
    {
        if ('sales_order_grid_data_source' !== $subject->getName()) return $result;
        if (!isset($result['sales_order_columns'])) return $result;

        foreach ($this->fieldPool->getFields() as $field) {
            /** @var FieldInterface $field */
            $config = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'component' => 'Magento_Ui/js/grid/columns/column',
                            'componentType' => 'column',
                            'label' => $field->getLabel(),
                            'sortable' => $field->isSortable(),
                            ...$field->getArguments()
                        ]
                    ]
                ],
            ];

            if ($class = $field->getColumnClass()) {
                $config['attributes'] = [
                    'class' => $class,
                    'component' => 'Magento_Ui/js/grid/columns/column'
                ];
            }

            $result['sales_order_columns']['children'][$field->getCode()] = $config;
        }
        return $result;
    }

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
