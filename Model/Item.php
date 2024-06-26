<?php
declare(strict_types=1);

namespace SwiftOtter\CustomerCollection\Model;

// Extend core Magento class
use Magento\Framework\Model\AbstractModel;

// Dependency injection
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Url as ProductUrl;
use Magento\Catalog\Helper\Image as ProductImage;

// Parent constructor classes
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;


class Item extends AbstractModel
{
    private ProductRepositoryInterface $productRepository;
    private ProductUrl $productUrl;
    private ProductImage $productImage;
    private $productDetails;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductUrl $productUrl,
        ProductImage $productImage,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        // Extend this object with extra classes we need
        $this->productRepository = $productRepository;
        $this->productUrl = $productUrl;
        $this->productImage = $productImage;

        // Run the parent constructor to execute default logic
        parent::__construct(
            $context, 
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    // _init defines the ResourceModel database object that will be used with the Model
    protected function _construct()
    {
        $this->_init(ResourceModel\Item::class);
    }

    /**
     * Load Magento product details using the ProductRepositoryInterface
     * 
     * @return ProductInterface|null
     */
    public function loadProductDetails(): ?ProductInterface
    {
        // Check if product details have already been loaded
        if ($this->productDetails) {
            return $this->productDetails;
        }

        // Get the product ID from this collection item (if it exists)
        $productId = $this->getData('product_id');

        if (!$productId) {
            return null;
        }
        
        // Load the associated product by ID
        try {
            $productDetails = $this->productRepository->getById($productId);
            return $productDetails;
        } 
        catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Get the product name associated with the item
     * @return string|null
     */
    public function getProductName(): string
    {
        return $this->loadProductDetails()->getName();
    }

    public function getProductUrl(): string
    {
        $product = $this->loadProductDetails();
        return $this->productUrl->getUrl($product);
    }

    public function getProductImageUrl(): string
    {
        $product = $this->loadProductDetails();
        return $this->productImage->init($product, 'product_base_image')->getUrl();
    }

    public function getComment(): string
    {
        return $this->getData('comment');
    }
}