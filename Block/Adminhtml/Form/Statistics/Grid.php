<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsStatistics\Block\Adminhtml\Form\Statistics;

/**
 *
 */
class Grid extends \Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Grid
{
    protected $_template = 'Alekseon_CustomFormsStatistics::form/statistics/grid.phtml';

    /**
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('form_statistics_grid');
        $this->setUseAjax(false);
    }

    /**
     * @return mixed
     */
    public function getFormFields()
    {
        $fields = $this->getCurrentForm()->getFieldsCollection();
        return $fields;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function getFieldHtml($field)
    {
        return $this->getLayout()->getBlock('field')
            ->setCollection($this->getCollection())
            ->setField($field)
            ->toHtml();
    }

    public function getNewRecordsHtml()
    {
        return $this->getLayout()->getBlock('new_records')
            ->setCollection($this->getCollection())
            ->toHtml();
    }

    protected function _prepareMassaction()
    {
    }

    protected function _setCollectionOrder($column)
    {
        return $this;
    }

    /**
     * @param $columnId
     * @param $column
     * @return Grid
     * @throws \Exception
     */
    public function addColumn($columnId, $column)
    {
        $column['sortable'] = false;
        if (!isset($column['filter']) || $column['filter']) {
            return parent::addColumn($columnId, $column);
        }
        return $this;
    }
}
