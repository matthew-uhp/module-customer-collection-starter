<?php
declare(strict_types=1);

namespace SwiftOtter\CustomerCollection\ViewModel\Customer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\CustomerCollection\Model\CachedProductsLoader;
use SwiftOtter\CustomerCollection\Model\CachedProductsLoaderFactory;

class CollectionList implements ArgumentInterface
{
    private ?array $collectionItems = null;
    private ?CachedProductsLoader $productsLoader = null;

    private CachedProductsLoaderFactory $cachedProductsLoaderFactory;

    public function __construct(
        CachedProductsLoaderFactory $cachedProductsLoaderFactory
        /** OBJECTIVE 10: Dependencies */
    ) {
        $this->cachedProductsLoaderFactory = $cachedProductsLoaderFactory;
    }

    public function getCollectionItems(): array
    {
        if ($this->collectionItems === null) {
            /** OBJECTIVE 10: Load My Collection items associated with the ID of the logged-in customer */
            $this->collectionItems = []; // TODO: Remove
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
