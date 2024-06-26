<?php
namespace SwiftOtter\CustomerCollection\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;

use Magento\Framework\View\Result\PageFactory AS ResultPageFactory;

class Index implements ActionInterface, HttpGetActionInterface
{
    private ResultPageFactory $resultPageFactory;

    public function __construct(
        ResultPageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        // Create a new results page
        return $this->resultPageFactory->create();
    }
}
