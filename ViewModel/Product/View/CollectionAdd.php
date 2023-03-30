<?php
declare(strict_types=1);

namespace SwiftOtter\CustomerCollection\ViewModel\Product\View;

use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\Http\Context;

class CollectionAdd implements ArgumentInterface
{
    private ?Product $product = null;

    private Registry $coreRegistry;
    private UrlInterface $url;
    private Context $httpContext;

    public function __construct(
        Registry $coreRegistry,
        UrlInterface $url,
        Context $httpContext
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->url = $url;
        $this->httpContext = $httpContext;
    }

    public function customerIsLoggedIn(): bool
    {
        return (bool) $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }

    public function getActionUrl(): string
    {
        return $this->url->getUrl('mycollection/item/add');
    }

    public function getProductId(): string
    {
        $product = $this->getProduct();
        return ($product !== null) ? (string) $product->getId() : '';
    }

    private function getProduct(): ?Product
    {
        if ($this->product === null) {
            $this->product = $this->coreRegistry->registry('product');
        }
        return $this->product;
    }
}
