<?php
/**
 * William G Redmond, Inc.

 *
 * @category    WGRedmond
 * @package     WGRedmond
 * @copyright   Copyright (c) William G Redmond, Inc. All rights reserved. (https://wgredmond.com/)
 */

namespace WGRedmond\FraudFighter\Observer\Events;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use \WGRedmond\FraudFighter\Logger\Logger;
use \WGRedmond\FraudFighter\Helper\Data;
use \WGRedmond\FraudFighter\Helper\FraudFighterConstants;
use \Magento\Customer\Model\Session;
use \Magento\Framework\Message\ManagerInterface;

use \WGRedmond\FraudFighter\Interfaces\EventsInterface;

class AccountLoginEvent implements ObserverInterface, EventsInterface
{

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Logger
     */
    protected $logger;
	
	/**
     * @var CustomerSession
     */
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
        Logger $logger,
		\Magento\Customer\Model\Session $customerSession,
		Data $dataHelper,
		FraudFighterConstants $constantsHelper
    ) {
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
	    $this->logger->info('>>>> In AccountLoginEvent <<<<<');

		$helper= $this->dataHelper;
		
		$constants= $this->constantsHelper;
        $event = $constants::LOGIN_EVENT_NAME;
	    $this->logger->info('AccountLoginEvent; $event = '.$event);

		$customer = $observer->getEvent()->getCustomer();
        // basic properties
        $properties = $this->addBasicProperties($customer);

        // custom properties
        $customProperties = $this->addCustomProperties($customer);
        //$properties = array_merge($properties, $customProperties);

        // add options
        $options = $this->addOptions();

        // If customer data is empty then doesn't need to process
        if (!$customer) {
            return $this;
        }

		// TODO: add logging
    }

    public function addBasicProperties($customer){
        $helper= $this->dataHelper;
        $customer_id = $customer->getId();
        $customer_email=$customer->getEmail();
        $session = $this->customerSession->getMyValue();
        $customer_agent = $_SERVER ['HTTP_USER_AGENT'];

        // If customer data is empty then doesn't need to process
        if (!$customer) {
            return $this;
        }

        // $login event
        $properties = array(
            // Required Fields
            '$user_id'    => $customer_id,
            '$session_id'    => $customer_id,
            '$login_status' => '$success',
            '$ip' => $helper->getRemoteIp(),

            // Optional Fields
            //'$failure_reason' => '$account_unknown',
            '$username'       => $customer_email,
            '$account_types'  => ['shopper'],

            '$browser'    => array(
                '$user_agent' =>  $customer_agent
            )

        );

        return $properties;

    }

    public function addCustomProperties($customer)
    {
        // Override to add custom properties
        $customProperties = array();

        // example
        //$customProperties = array(
        //  '$session_id'       => 'session-1234-5678',
        //  '$user_email'       => 'BOBBY@EXAMPLE.COM'
        //);

        return $customProperties;
    }


    public function addOptions()
    {
        // Override to add options
        $options = array();

        return $options;
    }
}
