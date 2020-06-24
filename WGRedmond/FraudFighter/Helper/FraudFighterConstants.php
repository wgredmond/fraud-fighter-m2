<?php
/**
 * William G. Redmond, Inc.
 *
 * @category    WGRedmond
 * @package     WGRedmond
 * @copyright   Copyright (c) William G. Redmond, Inc. All rights reserved. (https://wgredmond.com/)
 */

namespace WGRedmond\FraudFighter\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class FraudFighterConstants extends AbstractHelper
{
    //Constants Event

    //Customer events
    const CREATE_ACCOUNT_EVENT_NAME = '$create_account';
    const UPDATE_ACCOUNT_EVENT_NAME = '$update_account';
    const LOGIN_EVENT_NAME = '$login';
    const LOGOUT_EVENT_NAME = '$logout';

    //Order events
    const CREATE_ORDER_EVENT_NAME = '$create_order';


    //Log flags
    const isLogEnabled = true;
    const isLogDebugEnabled = false;
    const isLogErrorEnabled = true;

    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);

    }

}