<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsStatistics\Model\StatisticProvider;

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

        foreach (array_keys($options) as $key) {
            $labels[] = $key;
            $values[$key] = 0;
        }

        $chartData = parent::getChartData($collection);
        $average = 0;
        $totalCount = 0;
        $rateSum = 0;
        $notSelected = 0;
        foreach ($this->getChartValues() as $value => $count) {
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
        $chartData['height'] = 50;
        $chartData['info_array'] = [
            'Average' => round($average, 2),
            'Not Selected' => $notSelected,
        ];
        return $chartData;
    }
}
