<?php
declare(strict_types=1);

namespace SwiftOtter\CustomerCollection\Controller\Item;
// Implement core Magento classes
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;

// Dependency injections
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\Manager AS MessageManager;
use Magento\Framework\App\RequestInterface;
use SwiftOtter\CustomerCollection\Model\ResourceModel\Item AS ItemResourceModel;
use Magento\Customer\Model\Session as CustomerSession;
use SwiftOtter\CustomerCollection\Model\ItemFactory AS ItemModelFactory;


class Add implements ActionInterface, HttpPostActionInterface
{
    // Class properties
    private Validator $formKeyValidator;
    private RedirectFactory $redirectFactory;
    private RedirectInterface $redirect;
    private MessageManager $messageManager;
    private RequestInterface $request;
    private ItemResourceModel $itemResourceModel;
    private CustomerSession $customerSession;
    private ItemModelFactory $itemModelFactory;

    // Class constructor with dependency injection
    public function __construct(
        Validator $formKeyValidator,
        RedirectFactory $redirectFactory,
        RedirectInterface $redirect,
        MessageManager $messageManager,
        RequestInterface $request,
        ItemResourceModel $itemResourceModel,
        CustomerSession $customerSession,
        ItemModelFactory $itemModelFactory
    ) {
        $this->formKeyValidator = $formKeyValidator;
        $this->redirectFactory = $redirectFactory;
        $this->redirect = $redirect;
        $this->messageManager = $messageManager;
        $this->request = $request;
        $this->itemResourceModel = $itemResourceModel;
        $this->customerSession = $customerSession;
        $this->itemModelFactory = $itemModelFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        // Check form has valid security key
        $formKeyIsValid = $this->formKeyValidator->validate($this->request);

        if ($formKeyIsValid === false) {
            return $this->redirectToPreviousPageWithError("Invalid form key");
        }

        // Get customer ID from session
        $customerId = $this->customerSession->getCustomerId();
        
        if ($customerId === null) {
            return $this->redirectToPreviousPageWithError("Invalid customer ID");
        }

        // Get product ID and comment from form data
        $formFieldArray = $this->request->getParams();

        $productId = $formFieldArray['product_id'] ?? null;
        $comment = $formFieldArray['comment'] ?? null;

        if ($productId === null) {
            return $this->redirectToPreviousPageWithError("Missing product ID");
        }

        // Create a new collection item model and add data to it
        $itemModel = $this->itemModelFactory->create();

        $itemModel->setData('customer_id', $customerId);
        $itemModel->setData('product_id', $productId);
        $itemModel->setData('comment', $comment);

        // Get product name
        $productName = $itemModel->getProductName();

        if ($productName === null || $productName == "") {
            return $this->redirectToPreviousPageWithError("Invalid product ID");
        }

        // Save the collection item to the database and return message to user
        try {
            $this->itemResourceModel->save($itemModel);
            return $this->redirectToPreviousPageWithSuccess($productName." has been added to your collection.");
        }
        catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->redirectToPreviousPageWithError($e->getMessage());
        }
        catch (\Exception $e) {
            return $this->redirectToPreviousPageWithError("Failed to save");
        }
    }

    /**
     * Redirects the user back to the previous page with an error message
     * @param string $message
     * @return Redirect
     */
    private function redirectToPreviousPageWithError(string $message): Redirect
    {
        $errorMessagePrefix = 'An error occurred while adding this product to your collection - ';
        $this->messageManager->addErrorMessage(__($errorMessagePrefix.$message));

        return $this->redirectToPreviousPage();
    }

    /**
     * Redirects the user back to the previous page with a success message
     * @param string $message
     * @return Redirect
     */
    private function redirectToPreviousPageWithSuccess(string $message): Redirect
    {
        $this->messageManager->addSuccessMessage(__($message));

        return $this->redirectToPreviousPage();
    }

    /**
     * Redirects the user back to the previous page
     * @return Redirect
     */
    private function redirectToPreviousPage(): Redirect 
    {
        $redirect = $this->redirectFactory->create();
        $redirect->setUrl($this->redirect->getRefererUrl());
        return $redirect;
    }
}
