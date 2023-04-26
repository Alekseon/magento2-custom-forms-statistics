<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsStatistics\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 *
 */
class Index extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord implements HttpGetActionInterface
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $form = $this->initForm();

        if (!$form->getId()) {
            $this->_forward('noroute');
            return;
        }

        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__("Statistics for %1", $form->getTitle()));
        $this->_view->renderLayout();
    }
}
