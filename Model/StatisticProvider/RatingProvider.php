<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsStatistics\Model\StatisticProvider;

use Magento\Framework\DB\Select;
/**
 *
 */
class RatingProvider extends DefaultProvider
{
    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'Alekseon_CustomFormsStatistics::form/statistics/field/barChart.phtml';
    }

    /**
     * @param $collection
     * @return array[]
     */
    public function getChartData($collection)
    {
        $options = $this->getOptions();
        $labels = [];
        $values = [];
        foreach ($options as $key => $label) {
            $labels[] = $key;
            $values[$key] = 0;
        }

        $chartData = parent::getChartData($collection);
        foreach ($this->chartValues as $value => $count) {
            if (isset($values[$value])) {
                $values[$value] = $count;
            }
        }

        $chartData['values'] = array_values($values);
        $chartData['labels'] = $labels;
        $chartData['colors'] = ['#8549ba'];
        $chartData['type'] = 'bar';
        return $chartData;
    }
}
