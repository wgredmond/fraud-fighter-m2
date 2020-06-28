<?php

namespace WGRedmond\FraudFighter\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\StoreManagerInterface;
use \WGRedmond\FraudFighter\Logger\Logger;
use \Magento\Framework\Serialize\Serializer\Json;
use \WGRedmond\FraudFighter\Helper\FraudFighterConstants;


class Data extends AbstractHelper
{

    /**
     * Store Manager instance
     * @var \Magento\Store\Model\StoreManagerInterface
     */

    protected $_storeManager;

    /**
     * Logging instance
     * @var \WGRedmond\FraudFighter\Logger\Logger
     */

    protected $_logger;


    /**
     * Json instance
     * @var \Magento\Framework\Serialize\Serializer\Json;
     */

    protected $_json;

    /**
     * Json Helper instance
     * @var  \Magento\Framework\Json\Helper\Data;
     */

    protected $_jsonHelper;

    /**
     * Contstants Helper instance
     * @var  \WGRedmond\FraudFighter\Helper\FraudFighterConstants;
     */

    protected $_constantsHelper;
    protected $_configFunctions;

    //Array for Billing and Shipping Address
    public $billingAddress;
    public $shippingAddress;


    public function __construct(
        StoreManagerInterface $storeManager,
        Logger $logger,
        Json $json,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        FraudFighterConstants $constantsHelper,
        ConfigFunctions $configFunctions
    ) {
        $this->_storeManager = $storeManager;
        $this->_logger = $logger;
        $this->_json = $json;
        $this->_jsonHelper = $jsonHelper;
        $this->_constantsHelper = $constantsHelper;
        $this->_configFunctions = $configFunctions;
    }


    public function getBaseUrl() {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function setBillingAddress($customer, $billingAddress){

        $first_name= $customer->getFirstname();
        $last_name= $customer->getLastname();
        $customer_name = $first_name." ".$last_name;

        //Billing Address variables

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

        $this->billingAddress = array(
            '$name'         => $customer_name,
            '$phone'        => $billingTelephone,
            '$address_1'    => $billingAddress1,
            '$address_2'    => $billingAddress2,
            '$city'         => $billingCity,
            '$region'       => $billingRegion,
            '$country'      => 'US',
            '$zipcode'      => $billingZipcode
        );

    }

    public function setShippingAddress($customer, $shippingAddress){


        $first_name= $customer->getFirstname();
        $last_name= $customer->getLastname();
        $customer_name = $first_name." ".$last_name;

        //Shipping Address variables
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

        $this->shippingAddress = array(
            '$name'         => $customer_name,
            '$phone'        => $shippingTelephone,
            '$address_1'    => $shippingAddress1,
            '$address_2'    => $shippingAddress2,
            '$city'         => $shippingCity,
            '$region'       => $shippingRegion,
            '$country'      => 'US',
            '$zipcode'      => $shippingZipcode
        );

    }

    public function getBillingAddress(){
        return $this->billingAddress;
    }

    public function getShippingAddress(){
        return $this->shippingAddress;
    }

    /**
     *
     * @return string
     */
    public function getRemoteIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $remote_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $remote_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $remote_ip = $_SERVER['REMOTE_ADDR'];
        }
        return $remote_ip;
    }

    /**
     * @param $orderAmount
     * @return float|int
     */
    public function convertAmountToMicros($amount){

        if(is_numeric($amount) && $amount > 0){
            $amountInMicros = $amount * 1000000;
            return $amountInMicros;
        }
        else{
            return $amount;
        }
    }
	

	public function testFunction(){
        //test function that does nothing.
        $this->_logger->info("Actual function from Data.php");
    }

}