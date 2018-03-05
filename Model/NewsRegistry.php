<?php
/**
 * Copyright 2016 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Model;

use Aheadworks\News\Api\Data\NewsInterface;
use Aheadworks\News\Api\Data\NewsInterfaceFactory;
use Aheadworks\News\Model\NewsFactory;

/**
 * Registry for \Aheadworks\News\Api\Data\NewsInterface
 */
class NewsRegistry
{
    /**
     * @var array
     */
    private $newsRegistry = [];

    public function __construct(

    ) {

    }

    /**
     * Replace existing News with a new one
     *
     * @param NewsInterface $news
     * @return $this
     */
    public function push(NewsInterface $news)
    {
        if ($newsId = $news->getId()) {
            $this->newsRegistry[$newsId] = $news;
        }
        return $this;
    }
}
