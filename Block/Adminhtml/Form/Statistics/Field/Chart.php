<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsStatistics\Block\Adminhtml\Form\Statistics\Field;

/**
 *
 */
class Chart extends \Magento\Backend\Block\Template
{
    protected $_template = 'Alekseon_CustomFormsStatistics::form/statistics/field/chart.phtml';

    /**
     * @return mixed|string
     */
    public function getType()
    {
        return isset($this->getChartData()['type']) ? $this->getChartData()['type'] : 'pie';
    }
}
