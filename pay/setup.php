<?php

require_once dirname(__FILE__).'/conf.php';

class Pay_Setup extends Pay {

  private  $api_key;
  private  $token;

  private  $rootUrl = "";
  private  $rootTestUrl = "";

  private  $posturl = "/pay/v01/redirect/checkout-invoice/create";
  private  $geturl = "/pay/v01/redirect/checkout-invoice/confirm/";

  private  $withdrawal_posturl = "/pay/v01/withdrawal/create";
  private  $withdrawal_geturl = "/pay/v01/withdrawal/confirm/";
  private  $withdrawal_checkurl = "/pay/v01/withdrawal/check/";

  private  $cashdeposit_posturl = "/pay/v01/cashdeposit/create";
  private  $cashdeposit_geturl = "/pay/v01/cashdeposit/confirm/";

  private  $mode = "test";

  public function __construct(){

      switch (_PAYMENT_PLATFORM) {
          case "payplus":
              $this->rootUrl = "https://app.payplus.africa";
              $this->rootTestUrl = "https://apptest.payplus.africa";
              break;
          case "ligdicash":
              $this->rootUrl = "https://app.ligdicash.com";
              $this->rootTestUrl = "https://apptest.ligdicash.com";
              break;
          case "raycash":
              $this->rootUrl = "https://app.raycash.net";
              $this->rootTestUrl = "https://apptest.raycash.net";
              break;
          default:
              $this->rootUrl = "https://app.payplus.africa";
              $this->rootTestUrl = "https://apptest.payplus.africa";
              break;
      }

      //$this->rootUrl = "http://localhost/payplus";
      //$this->rootTestUrl = "http://localhost/payplus";
  }

  public  function setApi_key($api_key) {
    $this->api_key = $api_key;
  }
  public  function setToken($token) {
    $this->token = $token;
  }
  public  function setMode($mode) {
    $this->mode = $mode;
  }

  public  function getApi_key() {
    return $this->api_key;
  }
  public  function getToken() {
    return $this->token;
  }
  public  function getMode() {
    return $this->mode;
  }

  public function getCheckoutConfirmUrl() {
    if ($this->getMode() == "live") {
      return $this->rootUrl."".$this->geturl;
    }else{
      return $this->rootTestUrl."".$this->geturl;
    }
  }
  public  function getCheckoutBaseUrl() {
    if ($this->getMode() == "live") {
      return $this->rootUrl."".$this->posturl;
    }else{
      return $this->rootTestUrl."".$this->posturl;
    }
  }

    public function getWithdrawalCheckUrl() {
        if ($this->getMode() == "live") {
            return $this->rootUrl."".$this->withdrawal_checkurl;
        }else{
            return $this->rootTestUrl."".$this->withdrawal_checkurl;
        }
    }

  public function getWithdrawalConfirmUrl() {
       if ($this->getMode() == "live") {
            return $this->rootUrl."".$this->withdrawal_geturl;
       }else{
            return $this->rootTestUrl."".$this->withdrawal_geturl;
       }
  }

  public  function getWithdrawalBaseUrl() {
       if ($this->getMode() == "live") {
            return $this->rootUrl."".$this->withdrawal_posturl;
       }else{
            return $this->rootTestUrl."".$this->withdrawal_posturl;
       }
  }


    public function getCashdepositConfirmUrl() {
        if ($this->getMode() == "live") {
            return $this->rootUrl."".$this->cashdeposit_geturl;
        }else{
            return $this->rootTestUrl."".$this->cashdeposit_geturl;
        }
    }

    public  function getCashdepositBaseUrl() {
        if ($this->getMode() == "live") {
            return $this->rootUrl."".$this->cashdeposit_posturl;
        }else{
            return $this->rootTestUrl."".$this->cashdeposit_posturl;
        }
    }


    public  function insert(Pay_Setup $Payplus_Setup){
      $this->setApi_key($Payplus_Setup->getApi_key());
      $this->setToken($Payplus_Setup->getToken());
      $this->setMode($Payplus_Setup->getMode());
  }

}