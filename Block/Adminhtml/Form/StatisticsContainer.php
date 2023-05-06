<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsStatistics\Block\Adminhtml\Form;

/**
 *
 */
class StatisticsContainer extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * FormRecord constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_form_statistics';
        $this->_blockGroup = 'Alekseon_CustomFormsStatistics';
        parent::_construct();
        $this->removeButton('add');
        $this->addButton(
            'back',
            [
                'label' => __('Back'),
                'onclick' => 'setLocation(\'' . $this->getBackUrl() . '\')',
                'class' => 'back'
            ],
            -1
        );
    }

    public function getCurrentForm()
    {
        return $this->coreRegistry->registry('current_form');
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl(
            'alekseon_customFormsBuilder/formRecord',
            ['id' => $this->getCurrentForm()->getId()]
        );
    }
}
