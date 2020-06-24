<?php
/**
 * William G Redmond Inc.

 *
 * @category    WGRedmond
 * @package     WGRedmond
 * @copyright   Copyright (c) William G Redmond, Inc.. All rights reserved. (https://wgredmond.com/)
 */

namespace WGRedmond\FraudFighter\Observer\Events;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use \WGRedmond\FraudFighter\Helper\Data;
use \WGRedmond\FraudFighter\Helper\FraudFighterConstants;

class UpdateAccountAddress implements ObserverInterface
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
        $this->logger->info('In UpdateAccountAddress');

		$helper= $this->dataHelper;
		$constants= $this->constantsHelper;
        $event = $constants::UPDATE_ACCOUNT_EVENT_NAME;
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	  
	    //$customer = $observer->getEvent()->getCustomer();
		$customerAddress = $observer->getCustomerAddress();
        $customer = $customerAddress->getCustomer();
		
		
		
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
		$billingCompany = $billingAddress->getCompany();
		$billingTelephone = $billingAddress->getTelephone();
		$billingZipcode = $billingAddress->getPostcode();
		$billingCity = $billingAddress->getCity();
		$billingRegion = $billingAddress->getRegion();
		$billingStreet = $billingAddress->getStreet();
		$billingAddress1 = $billingStreet[0];
		$billingAddress2 = "";
		if(isset($billingStreet[1])){
			$billingAddress2 = $billingStreet[1];
		}	
		
		//Shipping Address variables
		$shippingId = $customer->getDefaultShipping();
		$shippingAddress = $objectManager->create('Magento\Customer\Model\Address')->load($shippingId);
		$shippingCompany = $shippingAddress->getCompany();
		$shippingTelephone = $shippingAddress->getTelephone();
		$shippingZipcode = $shippingAddress->getPostcode();
		$shippingCity = $shippingAddress->getCity();
		$shippingRegion = $shippingAddress->getRegion();
		$shippingStreet = $shippingAddress->getStreet();
		$shippingAddress1 = $shippingStreet[0];
		$shippingAddress2 = "";
		if(isset($shippingStreet[1])){
			$shippingAddress2 = $shippingStreet[1];
		}
	
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
		  '$changed_password' => False,
			'$session_id'       => $session,
		  '$user_email'       => $customer_email,
		  '$name'             => $customer_email,
		  '$phone'            => $billingTelephone,
		  '$referrer_user_id' => $referrer_user_id,
		 
		  '$billing_address'  => array(
			  '$name'         => $customer_name,
			  '$phone'        => $billingTelephone,
			  '$address_1'    => $billingAddress1,
			  '$address_2'    => $billingAddress2,
			  '$city'         => $billingCity,
			  '$region'       => $billingRegion,
			  '$country'      => 'US',
			  '$zipcode'      => $billingZipcode
		  ),
		  '$shipping_address' => array(
			  '$name'         => $customer_name,
			  '$phone'        => $shippingTelephone,
			  '$address_1'    => $shippingAddress1,
			  '$address_2'    => $shippingAddress2,
			  '$city'         => $shippingCity,
			  '$region'       => $shippingRegion,
			  '$country'      => 'US',
			  '$zipcode'      => $shippingZipcode
		  ),
		  
		  
		  
		   '$browser'    => array(
			'$user_agent' =>  $customer_agent
		  ) 
		);

        // TODO: add detail
        $this->logger->info('In UpdateAccountAddress; TODO');
    }
	
}
