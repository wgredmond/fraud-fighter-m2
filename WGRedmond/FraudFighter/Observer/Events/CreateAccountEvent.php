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
use Psr\Log\LoggerInterface;
use \WGRedmond\FraudFighter\Helper\Data;
use \WGRedmond\FraudFighter\Helper\FraudFighterConstants;

use \WGRedmond\FraudFighter\Interfaces\EventsInterface;

class CreateAccountEvent implements ObserverInterface, EventsInterface
{


    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;
	/**
     * @var CustomerFactory
     */
	protected $customerFactory;
	/**
     * @var AddressFactory
     */
	protected $addressFactory;
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
        LoggerInterface $logger,
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
        $this->logger->info('>>>> In CreateAccountEvent <<<<<');

		$helper= $this->dataHelper;
		$constants= $this->constantsHelper;
        $event = $constants::CREATE_ACCOUNT_EVENT_NAME;

        $customer = $observer->getEvent()->getCustomer();

        // basic properties
        $properties = $this->addBasicProperties($customer);

        // custom properties
        $customProperties = $this->addCustomProperties($customer);
        //$properties = array_merge($properties, $customProperties);

        // add options
        $options = $this->addOptions();

        // TODO: add loggings
    }


    public function addBasicProperties($customer){
        $helper= $this->dataHelper;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //Customer main info
        $customer_id = $customer->getId();
        $customer_email=$customer->getEmail();
        $first_name= $customer->getFirstname();
        $last_name= $customer->getLastname();
        $customer_name = $first_name." ".$last_name;
        $session = $this->customerSession->getMyValue();
        $referrer_user_id = $customer_id;
        $customer_agent = $_SERVER ['HTTP_USER_AGENT'];

        //Billing Address variables

        $billingID = $customer->getDefaultBilling();
        $billingAddress = $objectManager->create('Magento\Customer\Model\Address')->load($billingID);
        $billingTelephone = $billingAddress->getTelephone();
        $helper->setBillingAddress($customer,$billingAddress);

        //Shipping Address variables

        $shippingId = $customer->getDefaultShipping();
        $shippingAddress = $objectManager->create('Magento\Customer\Model\Address')->load($shippingId);
        $helper->setShippingAddress($customer,$shippingAddress);


        // If customer data is empty then doesn't need to process
        if (!$customer) {
            return $this;
        }


        // Sample $create_account event
        $properties = array(
            // Required Fields
            '$user_id'    => $customer_id,
            '$ip' => $helper->getRemoteIp(),

            // Supported Fields
            '$session_id'       => $session,
            '$user_email'       => $customer_email,
            '$name'             => $customer_name,
            '$phone'            => $billingTelephone,
            '$referrer_user_id' => $referrer_user_id,

            '$billing_address'  =>  $helper->getBillingAddress(),

            '$shipping_address' => $helper->getShippingAddress(),

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
        $options = array(
            'return_workflow_status' => True,
            'abuse_types' =>  array('payment_abuse')
        );

        return $options;
    }

}
