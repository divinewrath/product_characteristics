<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Controller\Adminhtml\Form;

use Bpfnet\ProductPictos\Model\PictoRepository;
use Exception;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Laminas\Uri\Uri;

class Save implements HttpPostActionInterface
{
    public function __construct(
        readonly protected RedirectFactory  $resultRedirectFactory,
        readonly protected RequestInterface $request,
        readonly protected PictoRepository  $pictoRepository,
        readonly protected ManagerInterface $messageManager,
        readonly protected StoreManagerInterface $storeManager,
        readonly protected ImageUploader $imageUploader,
        readonly protected File $file,
    ) {
    }

    public function execute(): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->request->getParams();
        if ($data) {
            if (empty($data['item_id'])) {
                $data['item_id'] = null;
            }

            $id = (int)$data['item_id'];
            if ($id) {
                try {
                    $picto = $this->pictoRepository->get($id);
                    $data = $this->processImageData($data);
                    $picto->setData($data);
                    $this->pictoRepository->save($picto);
                } catch (NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(__('This picto no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage(__('Something went wrong while saving the picto.'));
                }

                return $resultRedirect->setPath('*/*/index', ['item_id' => $id]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    protected function processImageData(array $data): array
    {
        $imageData = $data['image'] ?? null;
        unset($data['image']);
        if (isset($imageData[0]['tmp_name']) && ($imageName = $imageData[0]['name'])) {
            try {
                $mediaDir = $this->getStoreMediaDir();
                $newImgRelativePath = $this->imageUploader->moveFileFromTmp($imageName, true);
                $imageData[0]['url'] = '/' . $mediaDir . '/' . $newImgRelativePath;
            } catch (Exception $e) {
                $imageData = null;
            }
        } elseif (isset($imageData[0]['url'])) {
            $uri = new Uri($imageData[0]['url']);
            $imageData[0]['url'] = $uri->getPath();
        }

        if ($imageData !== null) {
            $pathInfo = $this->file->getPathInfo($imageData[0]['url']);
            $data['image'] = $pathInfo['basename'];
        }

        return $data;
    }

    protected function getStoreMediaDir(): string
    {
        return $this->storeManager->getStore()->getBaseMediaDir();
    }
}
