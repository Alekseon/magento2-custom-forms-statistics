<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsStatistics\Model\StatisticProvider;

use Alekseon\AlekseonEav\Model\ResourceModel\Entity\Collection;
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
        '#58c97b',
        '#8549ba'
    ];

    const OTHER_COLOR = '#888888';
    const NOT_SELECTED_COLOR = '#777777';

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
        $sourceModel = $this->attribute->getInputTypeModel()->getSourceModel();
        if ($sourceModel) {
            return $sourceModel->getOptions();
        } else {
            return [];
        }
    }

    /**
     * @return array[]
     */
    public function getChartData($collection)
    {
        $countExpr = new \Zend_Db_Expr('COUNT(*)');
        $collection->getSelect()->reset(Select::LIMIT_COUNT);
        $collection->getSelect()->reset(Select::GROUP);
        $collection->getSelect()->reset(Select::COLUMNS);
        $collection->getSelect()->reset(Select::ORDER);
        $collection->getSelect()->columns(
            [
                'count' => $countExpr
            ]
        );
        $results = $collection->getConnection()->fetchAll($collection->getSelect());

        $all = 0;
        if (isset($results[0]['count'])) {
            $all = $results[0]['count'];
        }

        $collection = clone $collection;

        $collection->addAttributeToFilter($this->attribute->getAttributeCode(), ['notnull' => true]);
        $valueExpr = new \Zend_Db_Expr(Collection::ATTRIBUTE_TABLE_ALIAS_PREFIX . $this->attribute->getAttributeCode() . '.value');
        $collection->getSelect()->columns([
            'count' => $countExpr,
            $this->attribute->getAttributeCode() => $valueExpr,
        ]);
        $collection->getSelect()->group($this->attribute->getAttributeCode());
        $collection->getSelect()->order(new \Zend_Db_Expr('count DESC'));

        $results = $collection->getConnection()->fetchAll($collection->getSelect());

        $labels = [];
        $values = [];
        $colors = [];

        $others = 0;

        $i = 0;
        $totalCount = 0;
        $options = $this->getOptions();

        foreach ($results as $result) {
            $value = $result[$this->attribute->getAttributeCode()];
            $count = $result['count'];
            $this->chartValues[$value] = $count;
            if ($value === null) {
                continue;
            }
            if (array_key_exists($value, $options)) {
                $totalCount += $count;
                $label = $options[$value] ?? $value;
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
            $colors[] = self::OTHER_COLOR;
            $values[] = $others;
            $labels[] = ' ' . __('Others');
        }

        $notSelected = $all - $totalCount;
        $this->chartValues[0] = $notSelected;

        if ($notSelected) {
            $colors[] = self::NOT_SELECTED_COLOR;
            $values[] = $notSelected;
            $labels[] = ' ' . __('Not Selected');
        }

        return [
            'records_count' => $all,
            'colors' => $colors,
            'labels' => $labels,
            'values' => $values,
            'type' => 'pie',
            'info_array' => [],
        ];
    }

}
