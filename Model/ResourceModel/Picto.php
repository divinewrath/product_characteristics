<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Picto extends AbstractDb
{
    protected $_eventPrefix = 'bpfnet_product_pictos_resource';

    protected function _construct()
    {
        $this->_init('bpfnet_product_pictos', 'item_id');
        $this->_useIsObjectNew = true;
    }
}
