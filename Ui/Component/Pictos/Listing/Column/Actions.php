<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Ui\Component\Pictos\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    public function __construct(
        ContextInterface      $context,
        UiComponentFactory    $uiComponentFactory,
        readonly UrlInterface $urlBuilder,
        readonly string       $editUrl = '',
        array                 $components = [],
        array                 $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['item_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['item_id' => $item['item_id']]),
                        'target' => '_blank',
                        'label' => __('Edit')
                    ];
                }
                $item['edit_url'] = $this->getData('editUrl');
            }
        }

        return $dataSource;
    }
}
