<?php
namespace WGRedmond\FraudFighter\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
// use \WGRedmond\FraudFighter\Logger\Logger;

/**
 * Class Index
 */
class Index extends Action implements HttpGetActionInterface
{
    const MENU_ID = 'WGRedmond_FraudFighter::suspectedfraud_dashboard';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

//    /**
//     * @var Logger
//     */
//    protected $logger;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
//        Logger $logger
    ) {
//        $this->logger->info('>>>> In dashboard Index.php <<<<<');

        parent::__construct($context);
//        $this->logger = $logger;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Load the page defined in view/adminhtml/layout/fraudfighter_dashboard_index.xml
     *
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(static::MENU_ID);
        $resultPage->getConfig()->getTitle()->prepend(__('Hello Fraud Fighters'));

        return $resultPage;
    }
}
