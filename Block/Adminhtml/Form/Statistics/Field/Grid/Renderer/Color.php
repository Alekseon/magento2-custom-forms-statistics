<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsStatistics\Block\Adminhtml\Form\Statistics\Field\Grid\Renderer;

/**
 *
 */
class Color extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        return '<div style="background-color: ' . $row->getColor() . '">&nbsp;</div>';
    }
}
