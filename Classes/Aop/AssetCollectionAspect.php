<?php

namespace CodeQ\AssetHelpers\Aop;

use CodeQ\AssetHelpers\Cache\ContentCacheFlusher;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\Exception\InvalidArgumentException;
use Neos\Flow\Aop\JoinPoint;
use Neos\Media\Domain\Model\AssetCollection;

/**
 * @Flow\Aspect
 */
class AssetCollectionAspect
{
    /**
     * @Flow\Inject
     * @var ContentCacheFlusher
     */
    protected $contentCacheFlusher;

    /**
     * @param  JoinPoint  $joinPoint
     * @return void
     * @Flow\After("method(Neos\Media\Domain\Repository\AssetCollectionRepository->update())")
     * @throws InvalidArgumentException
     */
    public function afterUpdateAssetCollection(JoinPoint $joinPoint): void
    {
        $assetCollection = $joinPoint->getMethodArgument('object');

        if (!$assetCollection instanceof AssetCollection) {
            return;
        }

        $this->clearCacheOnAssetCollectionChange($assetCollection);
    }

    /**
     * @param  JoinPoint  $joinPoint
     * @return void
     * @Flow\After("method(Neos\Media\Domain\Repository\AssetCollectionRepository->remove())")
     * @throws InvalidArgumentException
     */
    public function afterRemoveAssetCollection(JoinPoint $joinPoint): void
    {
        $assetCollection = $joinPoint->getMethodArgument('object');

        if (!$assetCollection instanceof AssetCollection) {
            return;
        }

        $this->clearCacheOnAssetCollectionChange($assetCollection);
    }

    protected function clearCacheOnAssetCollectionChange(AssetCollection $assetCollection): void
    {
        $this->contentCacheFlusher->flushForAssetCollection($assetCollection);
    }
}
