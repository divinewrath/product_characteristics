<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Bpfnet_ProductPictos::index_index';

    public function execute()
    {
        // TODO: Implement execute() method.
    }
}
