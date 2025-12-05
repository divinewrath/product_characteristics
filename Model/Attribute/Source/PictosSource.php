<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Model\Attribute\Source;

use Bpfnet\ProductPictos\Model\PictoRepository;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class PictosSource extends AbstractSource
{
    public function __construct(
        protected PictoRepository $pictoRepository,
    ) {
    }

    public function getAllOptions(): array
    {
        $pictos = $this->pictoRepository->getList();

        return array_map(static function ($picto) {
            return [
                'value' => $picto->getId(),
                'label' => $picto->getData('item_code'),
            ];
        }, $pictos->getItems());
    }
}
