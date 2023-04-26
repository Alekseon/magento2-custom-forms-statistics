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
        $values = [];
        $selectedValues = $this->getSelectedValues($optionLabels, $notSelected);

        $i = 0;
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

    /**
     * @param $optionLabels
     * @param $notSelected
     * @return array
     */
    private function getSelectedValues($optionLabels, &$notSelected)
    {
        $selectedValues = [];
        foreach ($this->chartValues as $value => $count) {
            if (!$value) {
                continue;
            }
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

        arsort($selectedValues);
        return $selectedValues;
    }
}
