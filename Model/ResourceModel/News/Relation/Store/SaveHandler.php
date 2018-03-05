<?php
/**
 * Copyright 2016 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Model\ResourceModel\News\Relation\Store;

use Magento\Framework\App\ResourceConnection;
use Aheadworks\News\Api\Data\NewsInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class SaveHandler
 * @package Aheadworks\News\Model\ResourceModel\News\Relation\Store
 */
class SaveHandler implements ExtensionInterface
{
    const DEFAULT_STORE_ID = 1;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(MetadataPool $metadataPool, ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
        $this->metadataPool = $metadataPool;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        $entityId = (int)$entity->getId();
        $status[] = $entity->getStatus();
        $statusOrig = $this->getStatus($entityId);
        $toInsert = array_diff($status, $statusOrig);
        $connection = $this->getConnection();
        $tableName = $this->resourceConnection->getTableName('aw_news_store');
        $connection->delete(
            $tableName,
            ['news_item_id = ?' => $entityId]
        );
        $data = [];
        if (count($toInsert)) {
            foreach ($toInsert as $status) {
                $data[] = [
                    'news_item_id' => $entityId,
                    'status' => $status,
                    'store_id' => SaveHandler::DEFAULT_STORE_ID,
                ];
            }
        } else {
            $data[] = [
                'news_item_id' => $entityId,
                'status' => $status[0],
                'store_id' => SaveHandler::DEFAULT_STORE_ID,
            ];
        }

        $connection->insertMultiple($tableName, $data);
        return $entity;
    }

    /**
     * @param $entityId
     * @return array
     * @throws \Exception
     */
    private function getStatus($entityId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->resourceConnection->getTableName('aw_news_store'), 'store_id')
            ->where('news_item_id = :id');
        return $connection->fetchCol($select, ['id' => $entityId]);
    }

    /**
     * Get connection
     *
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     * @throws \Exception
     */
    private function getConnection()
    {
        return $this->resourceConnection->getConnectionByName(
            $this->metadataPool->getMetadata(NewsInterface::class)->getEntityConnectionName()
        );
    }
}
