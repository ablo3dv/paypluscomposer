<?php

require_once dirname(__FILE__).'/conf.php';

class Pay_Utilities {

  protected $setup;
  protected $caller;

  // prevent instantiation of this class
  public function __construct(Pay_Setup $setup) {
      $this->setup = new Pay_Setup();
      $this->setup->insert($setup);
      $this->caller = 2;
  }

  public function httpJsonRequest($url,$data=array()){
    Requests::register_autoloader();
    $headers = array(
      'Accept' => 'application/json',
      'Apikey' => $this->setup->getApi_key(),
      'Authorization' => "Bearer ".$this->setup->getToken(),
      'Apicaller' => $this->caller
    );

    //$json_payload = json_encode($data);
    $commande = urlencode(json_encode($data));
    $request = Requests::post($url, $headers,array("commande"=>$commande),array('timeout' => 10));

    return json_decode($request->body,true);
  }

  public function httpPostRequest($url,$data=array()){
    Requests::register_autoloader();
    $headers = array(
      'Accept' => 'application/x-www-form-urlencoded',
      'Apikey' => $this->setup->getApi_key(),
      'Authorization' => "Bearer ".$this->setup->getToken(),
      'Apicaller' => $this->caller
    );

    $request = Requests::post($url, $headers,$data,array('timeout' => 10));

    return json_decode($request->body,true);
  }

  public function httpGetRequest($url){
    Requests::register_autoloader();
    $headers = array(
        'Apikey' => $this->setup->getApi_key(),
        'Authorization' => "Bearer ".$this->setup->getToken(),
        'Apicaller' => $this->caller
    );

    $request = Requests::get($url, $headers,array('timeout' => 10));

    return json_decode($request->body,true);
  }

}
