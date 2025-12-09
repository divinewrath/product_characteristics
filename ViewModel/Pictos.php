<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\ViewModel;

use Bpfnet\ProductPictos\Config\Constants;
use Bpfnet\ProductPictos\Model\Picto;
use Bpfnet\ProductPictos\Service\FindPictosByIds;
use Magento\Catalog\Model\Product;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

class Pictos implements ArgumentInterface
{
    public function __construct(
        readonly protected FindPictosByIds $findPictosByIds,
        readonly protected SerializerInterface $serializer,
        readonly protected StoreManagerInterface $storeManager,
    ) {
    }

    public function getPictos(Product $product)
    {
        $pictosIds = $product->getData('pictos');
        $pictosIds = $this->serializer->unserialize($pictosIds);

        return $this->findPictosByIds->execute($pictosIds);
    }

    public function getPictoImageUrl(Picto $picto): string
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return sprintf('%s%s/%s', $mediaUrl, Constants::PICTOS_MEDIA_PATH, $picto->getData('image'));
    }
}
