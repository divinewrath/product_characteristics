<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Controller\Adminhtml\Grid;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    public function __construct(
        readonly PageFactory $pageFactory,
    ) {
    }

    public function execute(): ResultInterface
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Bpfnet_ProductPictos::pictos');
        $resultPage->getConfig()->getTitle()->prepend(__('Pictos'));

        return $resultPage;
    }
}
