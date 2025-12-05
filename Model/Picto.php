<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Model;

use Bpfnet\ProductPictos\Model\ResourceModel\Picto as ResourceModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class Picto extends AbstractModel
{
    protected $_eventPrefix = 'bpfnet_product_pictos';

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
