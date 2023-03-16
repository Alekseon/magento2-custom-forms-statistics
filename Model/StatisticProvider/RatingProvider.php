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
        $average = 0;
        $totalCount = 0;
        $rateSum = 0;
        $notSelected = 0;
        foreach ($this->chartValues as $value => $count) {
            if (isset($values[$value])) {
                $values[$value] = $count;
                $rateSum += $value * $count;
                $totalCount += $count;
            } else {
                $notSelected += $count;
            }
        }
        if ($totalCount) {
            $average = $rateSum / $totalCount;
        }

        $colors = array_fill(0,  count($values), '#166a8f');
        $chartData['colors'] = $colors;
        $chartData['values'] = array_values($values);
        $chartData['labels'] = $labels;
        $chartData['type'] = 'bar';
        $chartData['info_array'] = [
            'Average' => round($average, 2),
            'Not Selected' => $notSelected,
        ];
        return $chartData;
    }
}
