<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsStatistics\Block\Adminhtml\Form\Statistics\Field;

use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DataObject;

/**
 *
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var bool
     */
    protected $_filterVisibility = false;
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    /**
     * @var
     */
    private $formAttribute;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory
    ) {
        $this->entityFactory = $entityFactory;
        parent::__construct($context, $backendHelper);
    }

    public function _construct()
    {
        parent::_construct();
        $this->setUseAjax(true);
    }

    /**
     * @param $attribute
     * @return $this
     */
    public function setFormAttribute($attribute)
    {
        $this->setId('field_statistics_grid_' . $attribute->getId());
        $this->formAttribute = $attribute;
        return $this;
    }

    /**
     * Initialize grid columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumn(
            'color',
            [
                'header' => '',
                'index' => 'color',
                'sortable' => false,
                'renderer' => \Alekseon\CustomFormsStatistics\Block\Adminhtml\Form\Statistics\Field\Grid\Renderer\Color::class,
            ]
        );

        $this->addColumn(
            'value',
            [
                'header' => __('Value'),
                'index' => 'value',
                'sortable' => false,
            ]
        );

        $this->addColumn(
            'count',
            [
                'header' => __('Count'),
                'index' => 'count',
                'sortable' => false,
            ]
        );

        return $this;
    }

    protected function _prepareCollection()
    {
        $collection = new Collection($this->entityFactory);
        $chartData = $this->getChartData();
        $values = $chartData['values'];
        foreach ($values as $i => $value) {
            $valueLabel = $chartData['labels'][$i] ?? '';
            $color = $chartData['colors'][$i] ?? false;
            $entity = $this->entityFactory->create(DataObject::class);
            $entity->setCount($value);
            $entity->setValue($valueLabel);
            $entity->setColor($color);
            $collection->addItem($entity);
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return false
     */
    public function getPagerVisibility()
    {
        return false;
    }

    /**
     * @param $item
     * @return false
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getRowUrl($item)
    {
        return false;
    }
}
