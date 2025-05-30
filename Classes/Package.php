<?php

namespace CodeQ\AssetHelpers;

use CodeQ\AssetHelpers\Cache\ContentCacheFlusher;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Media\Domain\Service\AssetService;

class Package extends BasePackage
{
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(AssetService::class, 'assetCreated', ContentCacheFlusher::class, 'flushForAsset', false);
        $dispatcher->connect(AssetService::class, 'assetUpdated', ContentCacheFlusher::class, 'flushForAsset', false);
        $dispatcher->connect(AssetService::class, 'assetRemoved', ContentCacheFlusher::class, 'flushForAsset', false);
        $dispatcher->connect(AssetService::class, 'assetResourceReplaced', ContentCacheFlusher::class, 'flushForAsset', false);
    }
}
