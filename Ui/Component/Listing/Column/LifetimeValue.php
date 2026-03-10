<?php

namespace Vendor\ExtendedOrderGrid\Ui\Component\Listing\Column;

use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Vendor\ExtendedOrderGrid\Model\Field\LifetimeValue as LifetimeValueModel;

class LifetimeValue extends Column
{
    public function __construct(
        protected PriceHelper $priceHelper,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) return $dataSource;

        foreach ($dataSource['data']['items'] as &$item) {
            if (isset($item[LifetimeValueModel::CODE])) {
                $item[$this->getData('name')] =
                    $this->priceHelper->currency(
                        $item[LifetimeValueModel::CODE],
                        true,
                        false
                    );
            }
        }
        return $dataSource;
    }
}
