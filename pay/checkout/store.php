<?php
class Pay_Checkout_Store extends Pay {
    private $name;
    private $websiteUrl;
    private $cancelUrl;
    private $returnUrl;
    private $callbackUrl;

  public function __construct(){}

  public function setName($name) {
    $this->name = $name;
  }

  public function setWebsiteUrl($url) {
    if(filter_var($url, FILTER_VALIDATE_URL)){
      $this->websiteUrl = $url;
    }
  }

  public function setCancelUrl($url) {
    if(filter_var($url, FILTER_VALIDATE_URL)){
      $this->cancelUrl = $url;
    }
  }

  public function setReturnUrl($url) {
    if(filter_var($url, FILTER_VALIDATE_URL)){
      $this->returnUrl = $url;
    }
  }

  public function setCallbackUrl($url) {
    if(filter_var($url, FILTER_VALIDATE_URL)){
      $this->callbackUrl = $url;
    }
  }

  public function getName() {
    return $this->name;
  }

  public function getWebsiteUrl() {
    return $this->websiteUrl;
  }

  public function getCancelUrl() {
    return $this->cancelUrl;
  }

  public function getReturnUrl() {
    return $this->returnUrl;
  }

  public function getCallbackUrl() {
    return $this->callbackUrl;
  }

  public function insert(Pay_Checkout_Store $Payplus_Checkout_Store){
      $this->setName($Payplus_Checkout_Store->getName());
      $this->setWebsiteUrl($Payplus_Checkout_Store->getWebsiteUrl());
      $this->setCancelUrl($Payplus_Checkout_Store->getCancelUrl());
      $this->setReturnUrl($Payplus_Checkout_Store->getReturnUrl());
      $this->setCallbackUrl($Payplus_Checkout_Store->getCallbackUrl());
  }
}