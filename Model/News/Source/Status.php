<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\News\Model\News\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 * @package Aheadworks\Data\Model\Data\Source
 */
class Status implements ArrayInterface
{
    const ENABLED = '1';
    const DISABLED = '0';

    /**
     * @var array
     */
    private $options;

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            self::ENABLED => __('Enabled'),
            self::DISABLED => __('Disabled'),
        ];
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];
            foreach ($this->getOptions() as $value => $label) {
                $this->options[] = ['value' => $value, 'label' => $label];
            }
        }
        return $this->options;
    }
}
