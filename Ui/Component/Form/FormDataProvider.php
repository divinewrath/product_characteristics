<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Ui\Component\Form;

use Bpfnet\ProductPictos\Helper\FileInfo;
use Bpfnet\ProductPictos\Model\PictoRepository;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

/**
 * DataProvider component.
 */
class FormDataProvider extends DataProvider
{
    protected array $loadedData = [];

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        readonly protected PictoRepository $pictoRepository,
        readonly protected FileInfo $fileInfo,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    public function getData(): array
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }

        $itemId = (int)$this->request->getParam($this->requestFieldName);
        try {
            $item = $this->pictoRepository->get($itemId);
            $this->loadedData[$itemId] = $item->getData();
            if (isset($this->loadedData[$itemId]['image'])) {
                $imageName = $this->loadedData[$itemId]['image'];
                unset($this->loadedData[$itemId]['image']);
                if (is_string($imageName) && $this->fileInfo->isExist($imageName)) {
                    $stat = $this->fileInfo->getStat($imageName);
                    $viewUrl = $this->fileInfo->getViewUrl($imageName);
                    $this->loadedData[$itemId]['image'] = [
                        [
                            'name' => $this->fileInfo->getBaseName($imageName),
                            'size' => $stat['size'],
                            'url' => $viewUrl,
                            'file' => $imageName,
                        ]
                    ];
                }
            }
        } catch (NoSuchEntityException $e) {
            // TODO: handle exception
            return [];
        }

        return $this->loadedData;
    }
}
