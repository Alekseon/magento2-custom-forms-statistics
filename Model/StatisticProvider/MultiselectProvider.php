<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsStatistics\Model\StatisticProvider;

class MultiselectProvider extends DefaultProvider
{
    public function getChartData($collection)
    {
        $selectedValues = [];
        $labels = [];
        $colors = [];

        $options = $this->getOptions();
        $chartData = parent::getChartData($collection);
        $optionLabels = [];
        foreach ($options as $optionId => $optionLabel) {
            $optionLabels[$optionId] = ' ' . $optionLabel;
        }

        $notSelected = $chartData['records_count'];
        $others = 0;
        foreach ($this->chartValues as $value => $count) {
            if ($value) {
                $selectedCount = 0;
                $selectedOptions = explode(',', $value);
                foreach ($selectedOptions as $optionId) {
                    if (isset($selectedValues[$optionId])) {
                        $selectedValues[$optionId] += $count;
                        $selectedCount = $count;
                        continue;
                    }
                    if (isset($optionLabels[$optionId])) {
                        $selectedValues[$optionId] = $count;
                        $selectedCount = $count;
                    }
                }
                if ($selectedCount) {
                    $notSelected -= $selectedCount;
                }
            }
        }

        $i = 0;
        arsort($selectedValues);

        $values = [];

        foreach ($selectedValues as $optionId => $count) {
            if (isset(self::COLORS[$i])) {
                $labels[] = $optionLabels[$optionId];
                $colors[] = self::COLORS[$i];
                $values[] =  $count;
                $i++;
            } else {
                $others += $count;
            }
        }

        if ($others) {
            $colors[] = self::OTHER_COLOR;
            $values[] = $others;
            $labels[] = ' ' . __('Others');
        }

        if ($notSelected) {
            $colors[] = self::NOT_SELECTED_COLOR;
            $values[] = $notSelected;
            $labels[] = ' ' . __('Not Selected');
        }

        $chartData['colors'] = $colors;
        $chartData['values'] = array_values($values);
        $chartData['labels'] = array_values($labels);

        return $chartData;
    }
}
