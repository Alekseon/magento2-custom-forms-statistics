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
class DefaultProvider
{
    protected $attribute;
    protected $chartValues = [];

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'Alekseon_CustomFormsStatistics::form/statistics/field/pieChartAndGrid.phtml';
    }

    const COLORS = [
        '#f67019',
        '#537bc4',
        '#acc236',
        '#166a8f',
        '#00a950',
        '#f53794',
        '#58595b',
        '#8549ba'
    ];

    /**
     * @param $attribute
     * @return $this
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function isApplicable()
    {
        return true;
    }

    /**
     * @return mixed
     */
    protected function getOptions()
    {
        return $this->attribute->getInputTypeModel()->getSourceModel()->getOptions();
    }

    /**
     * @return array[]
     */
    public function getChartData($collection)
    {
        $collection->addAttributeToSelect($this->attribute->getAttributeCode());
        $collection->getSelect()->reset(Select::GROUP);
        $collection->getSelect()->reset(Select::ORDER);
        $collection->addAttributeToFilter($this->attribute->getAttributeCode(), ['notnull' => true]);
        $countExpr = new \Zend_Db_Expr('COUNT(*)');
        $collection->getSelect()->columns(['count' => $countExpr]);
        $collection->getSelect()->group($this->attribute->getAttributeCode());
        $collection->getSelect()->order(new \Zend_Db_Expr('count DESC'));
        $results = $collection->getConnection()->fetchAll($collection->getSelect());

        $colors = [];
        $labels = [];
        $values = [];

        $all = $collection->count();
        $others = 0;

        $i = 0;
        $totalCount = 0;
        $options = $this->getOptions();
        foreach ($results as $result) {
            $value = $result[$this->attribute->getAttributeCode()];
            if (array_key_exists($value, $options)) {
                $count = $result['count'];
                $this->chartValues[$value] = $count;
                $label = $options[$value] ?? $value;
                $totalCount += $count;
                if (isset(self::COLORS[$i])) {
                    $colors[$i] = self::COLORS[$i];
                    $values[$i] = $count;
                    $labels[$i] = ' ' . $label;
                    $i ++;
                } else {
                    $others += $count;
                }
            }
        }

        if ($others) {
            $colors[$i] = '#888888';
            $values[$i] = $others;
            $labels[$i] = ' ' . __('Others');
            $i ++;
        }

        $notSelected = $all - $totalCount;

        if ($notSelected) {
            $colors[$i] = '#777777';
            $values[$i] = $notSelected;
            $labels[$i] = ' ' . __('Not Selected');
        }

        return [
            'colors' => $colors,
            'labels' => $labels,
            'values' => $values,
            'type' => 'pie',
        ];
    }
}
