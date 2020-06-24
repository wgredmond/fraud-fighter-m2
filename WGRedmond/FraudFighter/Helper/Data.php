<?php

namespace WGRedmond\FraudFighter\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\StoreManagerInterface;
use \WGRedmond\FraudFighter\Logger\Logger;
use \Magento\Framework\Serialize\Serializer\Json;


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

    public function __construct(
        StoreManagerInterface $storeManager,
        Logger $logger,
        Json $json,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    )
    {
        $this->_storeManager = $storeManager;
        $this->_logger = $logger;
        $this->_json = $json;
        $this->_jsonHelper = $jsonHelper;
    }


    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }


    public function getRemoteIp()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $remote_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $remote_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $remote_ip = $_SERVER['REMOTE_ADDR'];
        }
        return $remote_ip;
    }
}