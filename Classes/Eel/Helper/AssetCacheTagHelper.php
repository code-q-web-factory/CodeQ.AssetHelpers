<?php

namespace CodeQ\AssetHelpers\Eel\Helper;

use Behat\Transliterator\Transliterator;
use Generator;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\Tag;

/**
 * @Flow\Scope("singleton")
 */
class AssetCacheTagHelper implements ProtectedContextAwareInterface
{
    /**
     * @param  array<Asset>|Asset  $assets
     * @return array<string>
     */
    public function assetTag(array|AssetInterface $assets): array
    {
        if ($assets instanceof Asset) {
            $assets = [$assets];
        }

        $tags = [];

        /** @var Asset $asset */
        foreach ($assets as $asset) {
            $tags[] = sprintf('Asset_%s', $asset->getIdentifier());

            /** @var AssetCollection $collection */
            foreach ($asset->getAssetCollections() as $collection) {
                $tags[] = $this->assetCollectionTag($collection);
            }

            /** @var Tag $tag */
            foreach ($asset->getTags() as $tag) {
                $tags = [...$tags, ...$this->assetTagsCachingTag($tag)];
            }
        }

        return $tags;
    }

    /**
     * @param  AssetCollection  $assetCollection
     * @return string
     */
    public function assetCollectionTag(AssetCollection $assetCollection): string
    {
        return sprintf('AssetCollection_%s', Transliterator::urlize($assetCollection->getTitle()));
    }

    /**
     * @param  array|Tag  $tags
     * @return array<string>
     */
    public function assetTagsCachingTag(array|Tag $tags): array
    {
        if ($tags instanceof Tag) {
            $tags = [$tags];
        }

        $cachingTags = /**
         * @psalm-return Generator<int, string, mixed, void>
         */
        static function (array $tags): Generator {
            foreach ($tags as $tag) {
                yield sprintf('AssetTag_%s', Transliterator::urlize($tag->getLabel()));
            }
        };

        return iterator_to_array($cachingTags($tags));
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
