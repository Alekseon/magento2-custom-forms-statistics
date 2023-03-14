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

    /**
     *
     */
    public function getChartData()
    {
        $collection = $this->getCollection();
        $countExpr = new \Zend_Db_Expr('COUNT(*)');
        $dateExpr = new \Zend_Db_Expr('DATE(created_at)');
        $collection->getSelect()->columns(['count' => $countExpr]);
        $collection->getSelect()->columns(['date' => $dateExpr]);
        $collection->getSelect()->group($dateExpr);
        $collection->getSelect()->order(new \Zend_Db_Expr('date ASC'));

        $results = $collection->getConnection()->fetchAll($collection->getSelect());

        $data = [];
        $fromDate = null;
        $toDate = null;
        foreach ($results as $result) {
            $data[$result['date']] = $result['count'];
            if (is_null($fromDate)) {
                $fromDate = $result['date'];
            }
            $toDate = $result['date'];
        }

        $labels = [];
        $values = [];

        if ($fromDate && $toDate) {
            $date = $fromDate;
            while ($date <= $toDate) {
                $values[$date] = $data[$date] ?? 0;
                $labels[$date] = $date;
                $date = date('Y-m-d', strtotime($date .' +1 day'));

            }
        }
        
        $colors = array_fill(0,  count($values), '#4dc9f6');

        return [
            'labels' => array_values($labels),
            'values' => array_values($values),
            'colors' => $colors,
        ];
    }
}
