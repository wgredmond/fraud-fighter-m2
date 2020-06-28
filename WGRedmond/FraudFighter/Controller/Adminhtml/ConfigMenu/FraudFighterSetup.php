<?php

namespace WGRedmond\FraudFighter\Controller\Adminhtml\ConfigMenu;

use \Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\Json\Helper\Data;

class FraudFighterSetup extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $jsonHelper;

    public function __construct(Context $context, PageFactory $pageFactory, Data $jsonHelper )
    {
        $this->resultPageFactory = $pageFactory;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    public function execute()
    {

        $resultPage = $this->resultPageFactory->create();



		 $this->getRequest()->setParam('section','fraudfighter');
		 $resultPage->setActiveMenu('WGRedmond_FraudFighter::menu_item');
         $resultPage->addBreadcrumb(__('Fraud Fighter Configuration'),__('Fraud Fighter Setup'));
		 $resultPage->getConfig()->getTitle()->prepend(__('Fraud Fighter Configuration'));

        return $resultPage;

    }
}