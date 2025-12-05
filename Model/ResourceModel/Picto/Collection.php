<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Model\ResourceModel\Picto;

use Bpfnet\ProductPictos\Model\Picto as Model;
use Bpfnet\ProductPictos\Model\ResourceModel\Picto as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_eventPrefix = 'bpfnet_product_pictos_collection';

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
