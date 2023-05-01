<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsStatistics\Block\Adminhtml\Form\Statistics;

use Alekseon\CustomFormsStatistics\Model\StatisticProviderRepository;

/**
 *
 */
class Field extends \Magento\Backend\Block\Template
{
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
        $provider =  $this->statisticProviderRepository->getStatisticProviderByAttribute($this->getField());
        if ($provider && !$provider->isApplicable()) {
            return false;
        }
        return $provider;
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

        $this->_template = $this->getStatisticProvider()->getTemplate();

        return parent::_toHtml();
    }

    protected function getChartData()
    {
        if (!isset($this->chartData[$this->getField()->getId()])) {
            $this->chartData[$this->getField()->getId()] = $this->getStatisticProvider()->getChartData($this->getCollection());
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
        return $this->getChildBlock('chart')
            ->setField($this->getField())
            ->setChartData($chartData)
            ->toHtml();
    }

    public function getGridHtml()
    {
        $chartData = $this->getChartData();
        return $this->getChildBlock('grid')
            ->setField($this->getField())
            ->setChartData($chartData)
            ->toHtml();
    }

    public function getInfoArray()
    {
        return $this->getChartData()['info_array'];
    }
}
