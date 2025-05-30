<?php

namespace CodeQ\AssetHelpers\Eel\Helper;

use InvalidArgumentException;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Exception\InvalidQueryException;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\Tag;
use Neos\Media\Domain\Repository\AssetCollectionRepository;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Media\Domain\Repository\ImageRepository;
use Neos\Media\Domain\Repository\TagRepository;

/**
 * @Flow\Scope("singleton")
 */
class AssetHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var AssetRepository
     */
    protected $assetRepository;

    /**
     * @Flow\Inject
     * @var ImageRepository
     */
    protected $imageRepository;

    /**
     * @Flow\Inject
     * @var AssetCollectionRepository
     */
    protected $assetCollectionRepository;

    /**
     * @Flow\Inject()
     * @var TagRepository
     */
    protected $tagRepository;

    public function getCollectionByIdentifier(string $identifier): ?AssetCollection
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assetCollectionRepository->findByIdentifier($identifier);
    }

    public function getTagByIdentifier(string $identifier): ?Tag
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->tagRepository->findByIdentifier($identifier);
    }

    /**
     * @param  mixed  $collection
     * @param  string|null  $type
     * @return array
     * @throws InvalidQueryException
     */
    public function getAssetsByCollection(mixed $collection, ?string $type = null): array
    {
        if ($type !== null && $type !== 'image') {
            throw new InvalidArgumentException('Invalid type provided. Allowed types are "image" or "null" (all assets).', 1748432541210);
        }
        if (is_string($collection)) {
            $collection = $this->assetCollectionRepository->findByIdentifier($collection);
        }
        if (!$collection instanceof AssetCollection) {
            return [];
        }
        $assets = match ($type) {
            'image' => $this->imageRepository->findByAssetCollection($collection)->toArray(),
            default => $this->assetRepository->findByAssetCollection($collection)->toArray()
        };
        usort($assets, [__CLASS__, 'sortByTitleOrFilename']);
        return $assets;
    }

    /**
     * @param  mixed  $tag
     * @param  string|null  $type
     * @return array
     * @throws InvalidQueryException
     */
    public function getAssetsByTag(mixed $tag, ?string $type = null): array
    {
        if ($type !== null && $type !== 'image') {
            throw new InvalidArgumentException('Invalid type provided. Allowed types are "image" or "null" (all assets).', 1748432541210);
        }
        if (is_string($tag)) {
            $tag = $this->getTagByIdentifier($tag);
        }
        if (!$tag instanceof Tag) {
            return [];
        }
        $assets = match ($type) {
            'image' => $this->imageRepository->findByTag($tag)->toArray(),
            default => $this->assetRepository->findByTag($tag)->toArray()
        };
        usort($assets, [__CLASS__, 'sortByTitleOrFilename']);
        return $assets;
    }

    protected static function sortByTitleOrFilename(AssetInterface $a, AssetInterface $b): int
    {
        $aTitleOrFilename = $a->getTitle() ?: ucfirst(str_replace('_', '', preg_replace('/\\.[^.\\s]{3,4}$/', '', $a->getResource()->getFilename())));
        $bTitleOrFilename = $b->getTitle() ?: ucfirst(str_replace('_', '', preg_replace('/\\.[^.\\s]{3,4}$/', '', $b->getResource()->getFilename())));
        return strcmp($aTitleOrFilename, $bTitleOrFilename);
    }

    /**
     * Converts a byte-size integer to a human-readable size string
     * See: https://stackoverflow.com/questions/15188033/human-readable-file-size
     *
     * @param  int  $size
     * @return string
     */
    public function humanReadableFileSize(int $size): string
    {
        $decimalSeparator = ',';
        if ($size >= 1024 * 1024 * 1024) {
            $fileSize = number_format($size / 1024 / 1024 / 1024, 1, $decimalSeparator) . '&nbsp;GB';
        } elseif ($size >= 1024 * 1024) {
            $fileSize = number_format($size / 1024 / 1024, 1, $decimalSeparator) . '&nbsp;MB';
        } elseif ($size >= 1024) {
            $fileSize = number_format($size / 1024, 1, $decimalSeparator) . '&nbsp;KB';
        } else {
            $fileSize = $size . '&nbsp;B';
        }
        return $fileSize;
    }

    /**
     * @inheritDoc
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
