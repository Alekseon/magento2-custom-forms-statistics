<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsStatistics\Model\StatisticProvider;

/**
 *
 */
class SelectField
{
    const COLORS = [
        '#4dc9f6',
        '#f67019',
        '#f53794',
        '#537bc4',
        '#acc236',
        '#166a8f',
        '#00a950',
        '#58595b',
        '#8549ba'
    ];

    /**
     * @return array[]
     */
    public function getChartData($collection, $attribute)
    {
        $options = $attribute->getInputTypeModel()->getSourceModel()->getOptions();
        $collection->addAttributeToSelect($attribute->getAttributeCode());
        $collection = clone $collection;
        $collection->addAttributeToFilter([
            ['attribute' => $attribute->getAttributeCode(), ['notnull' => true]],
            ['attribute' => $attribute->getAttributeCode(), ['null' => true]]
        ]);
        $countExpr = new \Zend_Db_Expr('COUNT(*)');
        $collection->getSelect()->columns(['count' => $countExpr]);
        $collection->getSelect()->group($attribute->getAttributeCode());
        $collection->getSelect()->order(new \Zend_Db_Expr('count DESC'));

        $results = $attribute->getResource()->getConnection()->fetchAll($collection->getSelect());

        $colors = [];
        $labels = [];
        $values = [];

        $i = 0;
        foreach ($results as $result) {
            $value = $result[$attribute->getAttributeCode()];
            if (!$value) {
                $label = __('Not Selected');
            } else {
                $label = $options[$value] ?? $value;
            }
            $count = $result['count'];
            if (isset(self::COLORS[$i])) {
                $colors[$i] = self::COLORS[$i];
                $values[$i] = $count;
                $labels[$i] = ' ' . $label;
                $i ++;
            } else {
                $labels[$i - 1] = ' ' . __('Others');
                $values[$i - 1] += $count;
            }
        }


        return [
            'colors' => $colors,
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
