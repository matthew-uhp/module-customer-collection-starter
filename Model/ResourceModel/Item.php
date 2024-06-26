<?php
declare(strict_types=1);

namespace SwiftOtter\CustomerCollection\Model\ResourceModel;

// Extend core Magento class
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


class Item extends AbstractDb
{
    // _init defines the database table and primary key
    protected function _construct()
    {
        $this->_init('customer_collection_item', 'id');
    }

    // Extend AbstractDb _beforeSave method to determine if we are inserting or updating
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // Check if the object is already in the user's collection
        $customerId = $object->getData('customer_id');
        $productId = $object->getData('product_id');
        $existingRecordId = $this->checkIfItemInCollection($customerId, $productId);

        // If it is, add the existing record ID to the object so that it can be updated
        if ($existingRecordId !== null) {
            $object->setData('id', $existingRecordId);
        }

        // Run the parent method to execute default logic 
        return parent::_beforeSave($object);
    }

    /**
     * Check database to see if item is already in user's collection
     * 
     * @param int $customerId
     * @param int $productId
     * @return int|null Returns the record ID if the item is in the collection, otherwise null
     */
    public function checkIfItemInCollection($customerId, $productId): ?int
    {
        // Get connection to database
        $connection = $this->getConnection();

        // Create a select query to check if the item is already in the user's collection
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('customer_id = ?', $customerId)
            ->where('product_id = ?', $productId);

        // Try to get the first column of the first row, which contains the record ID
        $existingRecordId = $connection->fetchOne($select);

        if (empty($existingRecordId)) {
            return null;
        } 
        else {
            return (int) $existingRecordId;
        }
    }
}