<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Api\Data;

interface NewsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID                = 'id';
    const NEWS_TITLE        = 'title';
    const NEWS_DATE         = 'date';
    const STATUS            = 'status';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get Data Title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set Data Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get Data Description
     *
     * @return string|null
     */
    public function getDate();

    /**
     * Set Data Date
     *
     * @param string $createdAt
     * @return $this
     */
    public function setDate($date);

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\News\Api\Data\NewsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\News\Api\Data\NewsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Aheadworks\News\Api\Data\NewsExtensionInterface $extensionAttributes);
}
