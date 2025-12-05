<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Ui\DataProvider\Pictos;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Bpfnet\ProductPictos\Model\ResourceModel\Picto\CollectionFactory;

class ListingDataProvider extends AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
