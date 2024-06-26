<?php
declare(strict_types=1);

namespace SwiftOtter\CustomerCollection\ViewModel\Customer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\CustomerCollection\Model\CachedProductsLoader;
use SwiftOtter\CustomerCollection\Model\CachedProductsLoaderFactory;

use SwiftOtter\CustomerCollection\Model\ResourceModel\Item\CollectionFactory as ItemCollectionModelFactory;
use Magento\Customer\Model\Session as CustomerSession;


class CollectionList implements ArgumentInterface
{
    private ?array $collectionItems = null;
    private ?CachedProductsLoader $productsLoader = null;

    private CachedProductsLoaderFactory $cachedProductsLoaderFactory;
    private ItemCollectionModelFactory $itemCollectionModelFactory;
    private CustomerSession $customerSession;

    public function __construct(
        CachedProductsLoaderFactory $cachedProductsLoaderFactory,
        ItemCollectionModelFactory $itemCollectionModelFactory,
        CustomerSession $customerSession
    ) {
        $this->cachedProductsLoaderFactory = $cachedProductsLoaderFactory;
        $this->itemCollectionModelFactory = $itemCollectionModelFactory;
        $this->customerSession = $customerSession;
    }

    public function getCollectionItems(): array
    {
        if ($this->collectionItems === null) {

            // Get the current customer ID
            $customerId = $this->customerSession->getCustomerId();

            if ($customerId === null) {
                return [];
            }

            // Create collection model to store items, setting a filter to only return items for the logged-in customer
            $itemCollectionModel = $this->itemCollectionModelFactory->create();
            $itemCollectionModel->addCustomerIdFilter((int) $customerId);

            // Load the items from database
            $this->collectionItems = $itemCollectionModel->getItems();
        }

        return $this->collectionItems;
    }

    public function getProduct($productId): ?ProductInterface
    {
        $this->initProducts();
        return $this->productsLoader->getProduct($productId);
    }

    public function initProducts(): void
    {
        if ($this->productsLoader !== null) {
            return;
        }

        $collectionItems = $this->getCollectionItems();
        $productIds = [];
        foreach ($collectionItems as $collectionItem) {
            if ($collectionItem->hasData('product_id')) {
                $productIds[] = $collectionItem->getData('product_id');
            }
        }

        /** @var CachedProductsLoader productsLoader */
        $this->productsLoader = $this->cachedProductsLoaderFactory->create();
        $this->productsLoader->init($productIds);
    }
}
