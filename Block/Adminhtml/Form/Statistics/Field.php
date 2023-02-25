<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsStatistics\Block\Adminhtml\Form\Statistics;

use Alekseon\CustomFormsStatistics\Model\StatisticProviderRepository;

/**
 *
 */
class Field extends \Magento\Backend\Block\Template
{
    protected $_template = 'Alekseon_CustomFormsStatistics::form/statistics/field.phtml';

    /**
     * @var StatisticProviderRepository
     */
    protected $statisticProviderRepository;
    protected $chartData = [];

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param StatisticProviderRepository $statisticProviderRepository
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        StatisticProviderRepository $statisticProviderRepository
    ) {
        $this->statisticProviderRepository = $statisticProviderRepository;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    protected function getStatisticProvider()
    {
        return $this->statisticProviderRepository->getStatisticProviderByAttribute($this->getField());
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $statisticsProvider = $this->getStatisticProvider();
        if (!$statisticsProvider) {
            return '';
        }

        return parent::_toHtml();
    }

    protected function getChartData()
    {
        if (!isset($this->chartData[$this->getField()->getId()])) {
            $this->chartData[$this->getField()->getId()] = $this->getStatisticProvider()->getChartData($this->getCollection(), $this->getField());
        }

        return $this->chartData[$this->getField()->getId()];
    }

    /**
     * @param $field
     * @return string
     */
    public function getChartHtml()
    {
        $chartData = $this->getChartData();
        return $this->getChildBlock('chart')->setField($this->getField())->setChartData($chartData)->toHtml();
    }

    public function getGridHtml()
    {
        $chartData = $this->getChartData();
        return $this->getChildBlock('grid')->setField($this->getField())->setChartData($chartData)->toHtml();
    }
}
