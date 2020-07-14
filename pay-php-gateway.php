<?php
require_once("pay/dependency_check.php");

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__)));

abstract class Pay {
  const VERSION = "1.0.0";
  const SUCCESS = "success";
  const FAIL = "fail";
  const PENDING = "pending";
}

if (strnatcmp(phpversion(),'5.3.0') >= 0) {
  define('JSON_ENCODE_PARAM_SUPPORT',   true);
}else{
  define('JSON_ENCODE_PARAM_SUPPORT',   false);
}

require_once("pay/setup.php");
require_once("pay/customdata.php");
require_once("pay/checkout.php");
require_once("pay/checkout/store.php");
require_once("pay/checkout/checkout_invoice.php");
require_once("pay/checkout/checkout_invoice.php");
require_once("pay/withdrawal/withdrawal.php");
require_once("pay/cashdeposit/cashdeposit.php");
require_once("pay/libraries/Requests.php");
require_once("pay/utilities.php");