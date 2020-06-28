<?php
/**
 * William G Redmond, Inc.

 *
 * @category    WGRedmond
 * @package     WGRedmond
 * @copyright   Copyright (c) William G Redmond, Inc. All rights reserved. (https://wgredmond.com/)
 */

namespace WGRedmond\FraudFighter\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use \WGRedmond\FraudFighter\Logger\Logger;

class ConfigFunctions extends AbstractHelper
{

    /**
     * Logging instance
     * @var \WGRedmond\FraudFighter\Logger\Logger
     */

    protected $_logger;
    protected $scopeConfig;

    public function __construct(
        Logger $logger,
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_logger = $logger;
        $this->scopeConfig = $scopeConfig;
        // parent::__construct($context);

    }

    public function isLogEnabled($option){
        if($option){
            //Login is enabled
            $isLogEnabled = true;
        }
        else{
            $isLogEnabled = false;
        }
    }

    public function isDebugEnabled(){
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $option = $this->scopeConfig->getValue('WGRedmond/fraud_fighter_config/debug_enabled', $storeScope);
       // $this->_logger->info("\n DEBUG CONFIG OPTION : ".$option."\n");
        if($option == '1'){
            //Debug is enabled
           return true;
        }
        else{
            return false;

        }

    }
}