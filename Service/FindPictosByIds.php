<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Service;

use Bpfnet\ProductPictos\Model\PictoRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

class FindPictosByIds
{
    public function __construct(
        readonly protected PictoRepository $pictoRepository,
        readonly protected SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    public function execute(array $pictosIds): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('item_id', $pictosIds, 'in')
            ->create();

        $pictos = $this->pictoRepository->getList($searchCriteria)->getItems();

        $sortedPictos = [];
        foreach ($pictosIds as $order => $id) {
            if (isset($pictos[$id])) {
                $sortedPictos[$order] = $pictos[$id];
            }
        }

        return $sortedPictos;
    }
}
