<?php
namespace SwiftOtter\CustomerCollection\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;

class Index implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        /** OBJECTIVE 7: Dependencies */
    ) {

    }

    public function execute()
    {
        /** OBJECTIVE 7: Return appropriate result */
        die("Temporary collection page output");    // TODO Remove
    }
}
