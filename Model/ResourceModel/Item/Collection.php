<?php
declare(strict_types=1);

namespace SwiftOtter\CustomerCollection\Model\ResourceModel\Item;

// Extend core Magento class
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    // _init defines which Model/ResourceModel to use with collection
    protected function _construct()
    {
        $this->_init(
            \SwiftOtter\CustomerCollection\Model\Item::class,
            \SwiftOtter\CustomerCollection\Model\ResourceModel\Item::class
        );
    }

    // Filter the results of the collection by product ID
    public function addProductIdFilter(int $productId): Collection
    {
        $this->addFieldToFilter('product_id', $productId);
        return $this;
    }

    // Filter the results of the collection by customer ID
    public function addCustomerIdFilter(int $customerId): Collection
    {
        $this->addFieldToFilter('customer_id', $customerId);
        return $this;
    }
}