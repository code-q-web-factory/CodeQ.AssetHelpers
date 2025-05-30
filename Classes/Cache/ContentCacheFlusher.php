<?php

namespace CodeQ\AssetHelpers\Cache;

use CodeQ\AssetHelpers\Eel\Helper\AssetCacheTagHelper;
use Neos\Cache\Frontend\VariableFrontend;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\Tag;

/**
 * @Flow\Scope("singleton")
 */
class ContentCacheFlusher
{
    /**
     * @var array<string>
     */
    protected array $tagsToFlush = [];

    /**
     * @var VariableFrontend
     */
    protected $contentCache;

    /**
     * @Flow\Inject
     * @var AssetCacheTagHelper
     */
    protected $assetCacheTagHelper;

    /**
     * @param  Asset  $asset
     * @return void
     */
    public function flushForAsset(Asset $asset): void
    {
        $tags = $this->assetCacheTagHelper->assetTag($asset);
        $this->tagsToFlush = array_merge($this->tagsToFlush, $tags);
    }

    /**
     * @param  AssetCollection  $assetCollection
     * @return void
     */
    public function flushForAssetCollection(AssetCollection $assetCollection): void
    {
        $this->tagsToFlush[] = $this->assetCacheTagHelper->assetCollectionTag($assetCollection);
    }

    /**
     * @param  Tag  $tag
     * @return void
     */
    public function flushForTag(Tag $tag): void
    {
        $this->tagsToFlush[] = $this->assetCacheTagHelper->assetTagsCachingTag($tag);
    }


    public function __destruct()
    {
        $tagsToFlush = array_unique($this->tagsToFlush);
        foreach ($tagsToFlush as $tag) {
            $this->contentCache->flushByTag($tag);
        }
    }
}
