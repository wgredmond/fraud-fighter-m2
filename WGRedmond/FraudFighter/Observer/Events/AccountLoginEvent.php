<?php
/**
 * William G Redmond Inc.
 *
 * @category    WGRedmond
 * @package     WGRedmond
 * @copyright   Copyright (c) William G Redmond, Inc. All rights reserved. (https://wgredmond.com/)
 */

namespace WGRedmond\FraudFighter\Observer\Events;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use \WGRedmond\FraudFighter\Helper\Data;
use \WGRedmond\FraudFighter\Helper\FraudFighterConstants;

class AccountLoginEvent implements ObserverInterface
{


    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected $customerSession;

    /**
     * @var Data Helper
     */
    protected $dataHelper;


    /**
     * @var FraudFighterConstants Helper
     */
    protected $constantsHelper;


    public function __construct(
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        \Magento\Customer\Model\Session $customerSession,
        Data $dataHelper,
        FraudFighterConstants $constantsHelper
    )
    {

        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
        $this->dataHelper = $dataHelper;
        $this->constantsHelper = $constantsHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->info('In AccountLoginEvent');

        $helper = $this->dataHelper;

        $constants = $this->constantsHelper;
        $event = $constants::LOGIN_EVENT_NAME;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $observer->getEvent()->getCustomer();
        $customer_id = $customer->getId();
        $customer_email = $customer->getEmail();
        $ip_address = $helper->getRemoteIp();

        $session = $this->customerSession->getMyValue();

        $customer_agent = $_SERVER ['HTTP_USER_AGENT'];

        // If customer data is empty then doesn't need to process
        if (!$customer) {
            return $this;
        }

        // TODO: add detail
        $this->logger->info('In AccountLoginEvent; TODO');
    }

}
