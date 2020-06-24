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

class CreateOrderEvent implements ObserverInterface
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
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
       
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
		\Magento\Customer\Model\Session $customerSession,
		Data $dataHelper
    ) {
      
        $this->storeManager = $storeManager;
        $this->logger = $logger;
		$this->customerSession = $customerSession;
		$this->dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->info('In CreateOrderEvent');

		$CREATE_ORDER_EVENT = '$create_order';
		$helper= $this->dataHelper;

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	    $customer = $observer->getEvent()->getCustomer();

	    $order = $observer->getEvent()->getOrder();
	    $this->logger->info('In CreateOrderEvent, Order id = '.$order->getId());
		
		//$orderId = $this->getRequest()->getParam('order_id');
		//$order =  $this->$objectManager->create('Magento\Sales\Model\Order')->load($orderId);
		
		/*get customer details*/
		//$custLastName= $orders->getCustomerLastname();
		//$custFirsrName= $orders->getCustomerFirstname();
		//$ipaddress=$order->getRemoteIp();
		//$customer_email=$order->getCustomerEmail();
		//$customerid=$order->getCustomerId();
		$customer_agent = $_SERVER ['HTTP_USER_AGENT'];

		/* get Billing details */  
		$billingaddress=$order->getBillingAddress();
		$billingcity=$billingaddress->getCity();      
		$billingstreet=$billingaddress->getStreet();
		$billingpostcode=$billingaddress->getPostcode();
		$billingtelephone=$billingaddress->getTelephone();
		$billingstate_code=$billingaddress->getRegionCode();

		/* get shipping details */

		$shippingaddress=$order->getShippingAddress();        
		$shippingcity=$shippingaddress->getCity();
		$shippingstreet=$shippingaddress->getStreet();
		$shippingpostcode=$shippingaddress->getPostcode();      
		$shippingtelephone=$shippingaddress->getTelephone();
		$shippingstate_code=$shippingaddress->getRegionCode();
	
        // If order data is empty then doesn't need to process
        if (!$order) {
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
        $this->logger->info('In CreateOrderEvent; TODO');
    }
	
}
