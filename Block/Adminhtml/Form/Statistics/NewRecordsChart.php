<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsStatistics\Block\Adminhtml\Form\Statistics;

/**
 *
 */
class NewRecordsChart extends \Magento\Backend\Block\Template
{
    protected $_template = 'Alekseon_CustomFormsStatistics::form/statistics/newRecordsChart.phtml';

    public function getChartData()
    {
        $collection = $this->getCollection();

        $labels = [];
        $values = [];

        foreach ($collection as $item) {
            list($date, ) = explode(' ', $item->getCreatedAt());
            if (!isset($values[$date])) {
                $values[$date] = 0;
            }

            $values[$date]  ++;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'colors' => ['#4dc9f6'],
        ];
    }
}
