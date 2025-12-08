<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Ui\DataProvider\Product\Form\Modifier;

use Bpfnet\ProductPictos\Config\Constants;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\ArrayManager;

class Pictos extends AbstractModifier
{
    private const string FIELD_NAME = 'assigned_pictos';

    public function __construct(
        protected LocatorInterface    $locator,
        protected ArrayManager        $arrayManager,
        protected SerializerInterface $serializer
    ) {
    }

    public function modifyData(array $data): array
    {
        $product = $this->locator->getProduct();

        $data[$product->getId()] = $this->arrayManager->set(
            'pictos/assigned_pictos',
            $data[$product->getId()],
            $this->getSavedPictos($product)
        );

        return $data;
    }

    protected function getSavedPictos(Product $product): array
    {
        try {
            $pictos = $this->serializer->unserialize($product->getData('pictos'));

            return array_map(static function (int $itemKey, $itemValue) {
                return [
                    'record_id' => ++$itemKey,
                    'value' => $itemValue,
                    'position' => ++$itemKey,
                ];
            }, array_keys($pictos), array_values($pictos));
        } catch (\InvalidArgumentException $e) {
            return [];
        }
    }

    public function modifyMeta(array $meta): array
    {
        $meta = $this->removeOriginalPictosData($meta);

        return $this->addPictosData($meta);
    }

    protected function addPictosData(array $meta): array
    {
        $pictosPath = $this->arrayManager->findPath(
            Constants::ATTRIBUTE_CODE,
            $meta,
        );

        if ($pictosPath) {
            $meta = $this->arrayManager->merge(
                $pictosPath . static::META_CONFIG_PATH,
                $meta,
                [
                    'service' => [
                        'template' => 'ui/form/element/helper/service',
                    ]
                ]
            );
        }

        return $meta;
    }

    private function removeOriginalPictosData(array $meta): array
    {
        $pictosPath = $this->arrayManager->findPath(
            Constants::ATTRIBUTE_CODE,
            $meta,
        );

        $containerPath = $this->arrayManager->findPath(
            static::CONTAINER_PREFIX . Constants::ATTRIBUTE_CODE,
            $meta,
        );

        if (!$pictosPath) {
            return $meta;
        }

        return $this->arrayManager->remove($containerPath, $meta);
    }
}
