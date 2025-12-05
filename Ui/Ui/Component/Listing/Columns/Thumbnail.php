<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Ui\Ui\Component\Listing\Columns;

use Bpfnet\ProductPictos\Config\Constants;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Thumbnail extends Column
{
    protected StoreManagerInterface $storeManager;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $altField = $this->getData('config/altField');

            foreach ($dataSource['data']['items'] as & $item) {
                $imageUrl = '';
                if (!empty($item['image'])) {
                    $imageUrl = $this->getImageUrl($item['image']);
                }

                $item[$fieldName . '_src'] = $imageUrl;
                $item[$fieldName . '_orig_src'] = $imageUrl;
                $item[$fieldName . '_alt'] = '';
                $item[$fieldName . '_link'] = '';
            }
        }

        return $dataSource;
    }

    private function getImageUrl(string $image): string
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return sprintf('%s%s/%s', $mediaUrl, Constants::PICTOS_MEDIA_PATH, $image);
    }
}
