<?php
declare(strict_types=1);

namespace Bpfnet\ProductPictos\Helper;

use Bpfnet\ProductPictos\Config\Constants;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem\Io\File;

class FileInfo
{
    private Filesystem\Directory\WriteInterface $mediaDir;

    public function __construct(
        Filesystem $filesystem,
        readonly protected UrlInterface $urlBuilder,
        readonly protected EncoderInterface $urlEncoder,
        readonly protected File $file,


    ) {
        $this->mediaDir = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    public function isExist(string $filename): bool
    {
        $filePath = Constants::PICTOS_MEDIA_PATH . '/' . ltrim($filename, '/');

        return $this->mediaDir->isExist($filePath);
    }

    public function getStat(string $filename): array
    {
        $filePath = Constants::PICTOS_MEDIA_PATH . '/' . ltrim($filename, '/');

        return $this->mediaDir->stat($filePath);
    }

    public function getViewUrl(string $filePath, $type = 'zxc'): string
    {
        return sprintf(
            '%s%s%s',
            $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]),
            Constants::PICTOS_MEDIA_PATH . '/',
            $filePath,
        );
    }

    public function getBaseName(string $filename): string
    {
        $fileInfo = $this->file->getPathInfo($filename);

        return (string)$fileInfo['basename'];
    }
}
