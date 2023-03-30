<?php
declare(strict_types=1);

namespace SwiftOtter\CustomerCollection\Controller\Item;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ActionInterface;

class Add implements ActionInterface, HttpPostActionInterface
{
    public function __construct(
        /** OBJECTIVE 6: Dependencies */
    ) {

    }

    public function execute()
    {
        /** OBJECTIVE 6: Get data from request and save customer ID (from session),
         *      product ID and comment in new collection item
         */
    }
}
