<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsStatistics\Plugin;

use Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord;

/**
 *
 */
class AddStatisticsButtonPlugin
{
    /**
     * @param FormRecord $view
     * @return void
     */
    public function beforeSetLayout(FormRecord $gridContainer)
    {
        $form = $gridContainer->getCurrentForm();
        if ($form && $form->getId()) {
            $gridContainer->addButton(
                'create_refund',
                [
                    'label' => __('Statistics'),
                    'onclick' => 'setLocation(\'' . $this->getStatisticsUrl($gridContainer, $form) . '\')',
                ]
            );
        }
    }

    /**
     * @param $form
     * @return mixed
     */
    public function getStatisticsUrl($gridContainer, $form)
    {
        return $gridContainer->getUrl('alekseon_customFormsStatistics', ['id' => $form->getId()]);
    }
}
