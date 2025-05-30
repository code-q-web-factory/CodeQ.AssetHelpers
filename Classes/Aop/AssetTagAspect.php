<?php

namespace CodeQ\AssetHelpers\Aop;

use CodeQ\AssetHelpers\Cache\ContentCacheFlusher;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\Exception\InvalidArgumentException;
use Neos\Flow\Aop\JoinPoint;
use Neos\Media\Domain\Model\Tag;

/**
 * @Flow\Aspect
 */
class AssetTagAspect
{
    /**
     * @Flow\Inject
     * @var ContentCacheFlusher
     */
    protected $contentCacheFlusher;

    /**
     * @param  JoinPoint  $joinPoint
     * @return void
     * @Flow\After("method(Neos\Media\Domain\Repository\TagRepository->update())")
     * @throws InvalidArgumentException
     */
    public function afterUpdateTag(JoinPoint $joinPoint): void
    {
        $tag = $joinPoint->getMethodArgument('object');

        if (!$tag instanceof Tag) {
            return;
        }

        $this->clearCacheOnTagChange($tag);
    }

    /**
     * @param  JoinPoint  $joinPoint
     * @return void
     * @Flow\After("method(Neos\Media\Domain\Repository\AssetCollectionRepository->remove())")
     * @throws InvalidArgumentException
     */
    public function afterRemoveAssetCollection(JoinPoint $joinPoint): void
    {
        $tag = $joinPoint->getMethodArgument('object');

        if (!$tag instanceof Tag) {
            return;
        }

        $this->clearCacheOnTagChange($tag);
    }

    protected function clearCacheOnTagChange(Tag $tag): void
    {
        $this->contentCacheFlusher->flushForTag($tag);
    }
}
