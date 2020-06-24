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

class OrderPlaceAfter implements ObserverInterface
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
        $this->logger->info('In OrderPlaceAfter');

		$CREATE_ORDER_EVENT = '$transaction';
		$helper= $this->dataHelper;

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	    $customer = $observer->getEvent()->getCustomer();

	    $order = $observer->getEvent()->getOrder();
	    $this->logger->info('Order', $order);
		
		$customer_agent = $_SERVER ['HTTP_USER_AGENT'];
		$billingaddress=$order->getBillingAddress();
		$billingcity=$billingaddress->getCity();      
		$billingstreet=$billingaddress->getStreet();
		$billingpostcode=$billingaddress->getPostcode();
		$billingtelephone=$billingaddress->getTelephone();
		$billingstate_code=$billingaddress->getRegionCode();

		$shippingaddress=$order->getShippingAddress();        
		$shippingcity=$shippingaddress->getCity();
		$shippingstreet=$shippingaddress->getStreet();
		$shippingpostcode=$shippingaddress->getPostcode();      
		$shippingtelephone=$shippingaddress->getTelephone();
		$shippingstate_code=$shippingaddress->getRegionCode();
	    print_r($order);
		 if (!$order) {
            return $this;
        }

		
		$properties = array(
		 
		  '$user_id'    => $customer_id,
		  '$amount'           => 1200, 
		  '$currency_code'    => 'USD',
          '$ip' => $helper->getRemoteIp(),

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
		  '$payment_method'   => array(
		  '$payment_type'    => '$credit_card',
		  '$payment_gateway' => '$braintree',
		  '$card_bin'        => '542486',
		  '$card_last4'      => '4444'
			),
		  
		  '$browser'    => array(
			'$user_agent' =>  $customer_agent
		  ) 
		);

        // TODO: add detail
        $this->logger->info('In OrderPlaceAfter; TODO');
    }
	
}
