<?php

namespace Vendor\ExtendedOrderGrid\Model\Field;

use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Config\Share;
class LifetimeValue implements FieldInterface
{
    public const CODE = 'customer_ltv';

    protected array $cache = [];

    public function __construct(
        protected ResourceConnection $resourceConnection,
        protected StoreManagerInterface $storeManager,
        protected Share $shareConfig
    ) { }

    public function getCode(): string
    {
        return self::CODE;
    }

    public function calculate(array $orderRow): float
    {
        $customerId = $orderRow['customer_id'] ?? null;
        $email = $orderRow['customer_email'];
        $storeId = $orderRow['store_id'];

        // use id: for customer id cache keys to avoid collisions due to indices
        $cacheKey = $customerId ? 'id:' . $customerId : $email;
        if (isset($this->cache[$cacheKey])) return $this->cache[$cacheKey];

        $conn = $this->resourceConnection->getConnection();
        $orderTable = $this->resourceConnection->getTableName('sales_order');

        // @todo: abstract status into config
        $select = $conn->select()
            ->from($orderTable, ['lifetime_value' => 'SUM(grand_total)'])
            ->where('status in (?)', ['complete', 'processing', 'pending_payment', 'pending_paypal']);

        if ($customerId) {
            $select->where('customer_id = ?', $customerId);
        }
        else {
            $select->where('customer_email = ?', $email);
        }

        // check if customers are shared globally or not
        if (!$this->shareConfig->isGlobalScope()) {
            $website = $this->storeManager->getStore($storeId)
                ->getWebsite();

            if ($website) {
                $select->where('store_id in (?)', $website->getStoreIds());
            }
        }

        $res = (float)$conn->fetchOne($select);
        $this->cache[$cacheKey] = $res;
        return $res;
    }

    public function isSortable(): bool
    {
        return true;
    }

    public function getArguments(): array
    {
        return [
            'visible' => false,
            'filter' => 'textRange'
        ];
    }

    public function getLabel(): string
    {
        return __('Lifetime Revenue');
    }

    public function getColumnClass(): ?string
    {
        return \Vendor\ExtendedOrderGrid\Ui\Component\Listing\Column\LifetimeValue::class;
    }
}
