<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Controller\Adminhtml\Grid;

use Bpfnet\ProductPictos\Model\PictoRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Message\ManagerInterface;

class MassDelete implements HttpPostActionInterface
{
    public const string ADMIN_RESOURCE = 'Bpfnet_ProductPictos::index_index';

    public function __construct(
        protected readonly RequestInterface $request,
        protected readonly PictoRepository $pictoRepository,
        protected readonly ManagerInterface $messageManager,
        protected readonly RedirectFactory $resultRedirectFactory
    ) {
    }

    public function execute(): ResultInterface
    {
        $idsToDelete = (array)$this->request->getParam('selected', []);

        try {
            foreach ($idsToDelete as $idToDelete) {
                $this->pictoRepository->deleteById((int)$idToDelete);
            }
            $this->messageManager->addSuccessMessage(__('The selected pictos have been deleted.'));
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage(__('There was an error while deleting pictos'));
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
