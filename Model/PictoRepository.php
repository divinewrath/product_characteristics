<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Model;

use Bpfnet\ProductPictos\Model\ResourceModel\Picto as PictoResource;
use Bpfnet\ProductPictos\Model\ResourceModel\Picto\Collection as PictoCollection;
use Bpfnet\ProductPictos\Model\ResourceModel\Picto\CollectionFactory as PictoCollectionFactory;
use Bpfnet\ProductPictos\Model\Picto;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Exception;

class PictoRepository
{
    public function __construct(
        protected PictoResource $pictoResource,
        protected PictoFactory $pictoFactory,
        protected PictoCollectionFactory $pictoCollectionFactory,
        protected CollectionProcessorInterface $collectionProcessor,
        protected SearchResultsInterfaceFactory $searchResultsFactory
    ) {
    }

    /**
     * @throws NoSuchEntityException
     */
    public function get(int $pictoId): Picto
    {
        /** @var Picto $picto */
        $picto = $this->pictoFactory->create();
        $this->pictoResource->load($picto, $pictoId);
        if (!$picto->getId()) {
            throw new NoSuchEntityException(__('Picto with id "%1" does not exist.', $pictoId));
        }

        return $picto;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(Picto $picto): Picto
    {
        try {
            $this->pictoResource->save($picto);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $picto;
    }

    public function getList(?SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface
    {
        /** @var PictoCollection $collection */
        $collection = $this->pictoCollectionFactory->create();
        $searchResults = $this->searchResultsFactory->create();
        if ($searchCriteria) {
            $this->collectionProcessor->process($searchCriteria, $collection);
            $searchResults->setSearchCriteria($searchCriteria);
        }
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(Picto $picto): bool
    {
        try {
            $this->pictoResource->delete($picto);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    public function deleteById(int $pictoId): bool
    {
        try {
            $picto = $this->get($pictoId);
            $this->pictoResource->delete($picto);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }
}
