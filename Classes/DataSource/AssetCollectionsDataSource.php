<?php

namespace CodeQ\AssetHelpers\DataSource;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\PersistenceManager;
use Neos\Media\Domain\Model\Tag;
use Neos\Media\Domain\Repository\AssetCollectionRepository;
use Neos\Media\Domain\Repository\TagRepository;
use Neos\Neos\Service\DataSource\AbstractDataSource;

class AssetCollectionsDataSource extends AbstractDataSource
{
    protected static $identifier = 'codeq-assethelper-collections';

    /**
     * @Flow\Inject()
     * @var AssetCollectionRepository
     */
    protected $assetCollectionRepository;

    /**
     * @Flow\Inject()
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @inheritDoc
     */
    public function getData(NodeInterface $node = null, array $arguments = [])
    {
        $assetCollections = $this->assetCollectionRepository->findAll();
        $result = [];
        foreach ($assetCollections as $assetCollection) {
            $result[] = [
                'label' => $assetCollection->getTitle(),
                'value' => $this->persistenceManager->getIdentifierByObject($assetCollection)
            ];
        }
        return $result;
    }
}
