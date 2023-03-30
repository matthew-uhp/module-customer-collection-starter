<?php
declare(strict_types=1);

namespace SwiftOtter\CustomerCollection\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class CachedProductsLoader
{
    private ?array $products = null;

    private ProductRepositoryInterface $productRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function init(array $productIds): void
    {
        if ($this->products !== null) {
            return;
        }

        $this->searchCriteriaBuilder->addFilter('entity_id', $productIds, 'in');
        $this->products = $this->productRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }

    /**
     * @param string|int $productId
     */
    public function getProduct($productId): ?ProductInterface
    {
        return $this->products[$productId] ?? null;
    }
}
