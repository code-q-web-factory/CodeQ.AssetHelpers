<?php

namespace CodeQ\AssetHelpers\DataSource;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\PersistenceManager;
use Neos\Media\Domain\Model\Tag;
use Neos\Media\Domain\Repository\TagRepository;
use Neos\Neos\Service\DataSource\AbstractDataSource;

class AssetTagsDataSource extends AbstractDataSource
{
    protected static $identifier = 'codeq-assethelper-tags';

    /**
     * @Flow\Inject()
     * @var TagRepository
     */
    protected $tagRepository;

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
        $tags = $this->tagRepository->findAll();
        $result = [];
        /** @var Tag $tag */
        foreach ($tags as $tag) {
            $result[] = [
                'label' => $tag->getLabel(),
                'value' => $this->persistenceManager->getIdentifierByObject($tag)
            ];
        }
        return $result;
    }
}
