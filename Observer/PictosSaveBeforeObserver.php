<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Observer;

use Bpfnet\ProductPictos\Config\Constants;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;

class PictosSaveBeforeObserver implements ObserverInterface
{
    public function __construct(
        readonly protected Json $serializer,
        readonly protected RequestInterface $request
    ) {
    }

    /**
     * @throws LocalizedException
     */
    public function execute(Observer $observer): void
    {
        /** @var Product $product */
        $product = $observer->getEvent()->getProduct();

        $pictosSaveData = $this->request->getParam('pictos');

        if (!isset($pictosSaveData['assigned_pictos']) || !is_array($pictosSaveData['assigned_pictos'])) {
            $product->unsetData(Constants::ATTRIBUTE_CODE);
            return;
        }

        $assignedPictos = $pictosSaveData['assigned_pictos'];
        usort($assignedPictos, static function ($a, $b) {
            return ($a['position'] ?? 0) <=> ($b['position'] ?? 0);
        });

        $pictoIds = [];
        foreach ($assignedPictos as $pictoData) {
            if (is_array($pictoData) && isset($pictoData['value'])) {
                $pictoId = $pictoData['value'];
                if (is_numeric($pictoId) && $pictoId > 0) {
                    $pictoIds[] = (int)$pictoId;
                }
            }
        }

        if (empty($pictoIds)) {
            $product->unsetData(Constants::ATTRIBUTE_CODE);
            return;
        }

        try {
            $serializedPictos = $this->serializer->serialize($pictoIds);
            $product->setData(Constants::ATTRIBUTE_CODE, $serializedPictos);
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Failed to serialize pictos data: %1', $e->getMessage())
            );
        }
    }
}
