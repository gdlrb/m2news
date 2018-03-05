<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Aheadworks\News\Api\Data\NewsInterface;

class News extends AbstractModel implements NewsInterface, IdentityInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'aw_news';

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Aheadworks\News\Model\ResourceModel\News');
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(NewsInterface::NEWS_TITLE);
    }

    /**
     * Set title
     *
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(NewsInterface::NEWS_TITLE, $title);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getDate()
    {
        return $this->getData(NewsInterface::NEWS_DATE);
    }

    /**
     * Set created at
     *
     * @param $newsDate
     * @return $this
     */
    public function setDate($newsDate)
    {
        return $this->setData(NewsInterface::NEWS_DATE, $newsDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(\Aheadworks\News\Api\Data\NewsExtensionInterface $extensionAttributes)
    {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        $identities = [self::CACHE_TAG . '_' . $this->getId()];
        if ($this->_appState->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML) {
            $identities[] = self::CACHE_TAG;
        }
        return $identities;
    }
}
