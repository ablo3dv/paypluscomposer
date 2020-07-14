<?php
class Pay_Checkout_Invoice extends Pay_Checkout {

  protected $items = array();
  protected $total_amount = 0.0;
  protected $taxes = array();
  protected $currency = "fcfa";
  protected $invoice_url;
  protected $custom_data;
  protected $receipt_url;

  protected $customer = array();
  protected $store;
  protected $setup;
  protected $utility;

  protected $operator_id = "";
  protected $operator_name = "";

  function __construct(Pay_Checkout_Store $store, Pay_Setup $setup){
    $this->custom_data = new Pay_CustomData();
    $this->store = new Pay_Checkout_Store();
    $this->setup = new Pay_Setup();
    $this->store->insert($store);
    $this->setup->insert($setup);
    $this->utility = new Pay_Utilities($setup);
  }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }



  public function addItem($name,$quantity,$price,$totalPrice,$description="") {
    $this->items['item_'.count($this->items)] = array(
      'name' => $name,
      'quantity' => intval($quantity),
      'unit_price' => round($price,2),
      'total_price' => round($totalPrice,2),
      'description' => $description
    );
  }

  public function pushItems($data=array()) {
    $this->items = $data;
  }


  public function pushTaxes($data=array()) {
    $this->taxes = $data;
  }

  public function setTotalAmount($amount) {
    $this->total_amount = round($amount,2);
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function setCancelUrl($url) {
    if(filter_var($url, FILTER_VALIDATE_URL)){
      $this->cancel_url = $url;
    }
  }

  public function setReturnUrl($url) {
    if(filter_var($url, FILTER_VALIDATE_URL)){
      $this->return_url = $url;
    }
  }

  public function setCallbackUrl($url) {
    if(filter_var($url, FILTER_VALIDATE_URL)){
      $this->callback_url = $url;
    }
  }

  public function addTax($name,$amount) {
    $this->taxes['tax_'.count($this->taxes)] = array(
      'name' => $name,
      'amount' => $amount
    );
  }

  public function getInvoiceUrl() {
    return $this->invoice_url;
  }

  public function getItems() {
    return json_encode($this->items, JSON_FORCE_OBJECT);
  }

  public function getTaxes() {
    return json_encode($this->taxes, JSON_FORCE_OBJECT);
  }

  public function getCustomerInfo($info_type) {
    return $this->customer[$info_type];
  }

  public function addCustomData($name,$value) {
    $this->custom_data->set($name,$value);
  }

  public function pushCustomData($data=array()) {
    $this->custom_data->push($data);
  }

  public function getCustomData($name) {
    return $this->custom_data->get($name);
  }



  public function showCustomData() {
    return $this->custom_data->show();
  }

  public function getTotalAmount() {
    return $this->total_amount;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getReceiptUrl() {
    return $this->receipt_url;
  }

  public function getStatus() {
    return $this->status;
  }

   public function getOperator_id(){
     return $this->operator_id;
   }

   public function getOperator_name(){
     return $this->operator_name;
   }


    public function confirm($token="") {
    $mtoken = trim($token);
    if (empty($mtoken)) {
      $mtoken = $_GET['token'];
    }

    $result = $this->utility->httpGetRequest($this->setup->getCheckoutConfirmUrl()."?invoiceToken=" . $mtoken);
    if(count($result) > 0) {
      switch ($result['status']) {
        case 'completed':
          $this->status = $result['status'];
          if(isset($result["custom_data"])){
              $this->pushCustomData($result["custom_data"]);
          }
            if(isset($result["invoice"])){
                $this->pushItems($result["invoice"]['items']);
                $this->pushTaxes($result["invoice"]['taxes']);
                $this->setTotalAmount($result['invoice']['total_amount']);
            }
            if(isset($result['customer'])){
                $this->customer = $result['customer'];
            }
            if(isset($result['montant'])){
                $this->total_amount = $result['montant'];
            }
            if(isset($result['token'])){
                $this->token = $result['token'];
            }
            if(isset($result['receipt_url'])){
                $this->receipt_url = $result['receipt_url'];
            }
            $this->response_text = "Invoice status is ".strtoupper($result['status']);
            $this->operator_id = "".$result['operator_id'];
            $this->operator_name = "".$result['operator_name'];
          return true;
          break;
        default:
          $this->status = $result['status'];
            if(isset($result["custom_data"])){
                $this->pushCustomData($result["custom_data"]);
            }
            if(isset($result["invoice"])){
                $this->pushItems($result["invoice"]['items']);
                $this->pushTaxes($result["invoice"]['taxes']);
                $this->setTotalAmount($result['invoice']['total_amount']);
            }
          $this->response_text = "Invoice status is ".strtoupper($result['status']);
          return false;
      }
    }else{
      $this->status = Pay::FAIL;
      $this->response_code = 1002;
      $this->response_text = "Invoice Not Found";
      return false;
    }
  }

  public function create() {
    $checkout_payload = array(
      'invoice' => array(
        'items' => $this->items,
        'total_amount' => $this->getTotalAmount(),
        'description' => $this->getDescription(),
      ),
      'store' => array(
        'name' => $this->store->getName(),
        'website_url' => $this->store->getWebsiteUrl()
      ),
      'custom_data' => $this->showCustomData(),
      'actions' => array(
        'cancel_url' => $this->store->getCancelUrl(),
        'return_url' => $this->store->getReturnUrl(),
        'callback_url' => $this->store->getCallbackUrl()
      )
    );

     /*
     print_r($checkout_payload);
      echo '<br><br>';
     print_r($this->setup);
      echo '<br><br>';
      echo $this->setup->getCheckoutBaseUrl();
      echo '<br><br>';
     */

     //echo '2<br>';
    $result = $this->utility->httpJsonRequest($this->setup->getCheckoutBaseUrl(),$checkout_payload);
      //echo '3<br>';
	//echo $this->setup->getCheckoutBaseUrl().'<br>';
    //echo print_r($result); exit(); //$result["response_code"].'<br>'.$result["response_text"]; exit();

    switch ($result["response_code"]) {
      case "00":
        $this->status = Pay::SUCCESS;
        $this->token = $result["token"];
        $this->response_code = $result["response_code"];
        $this->response_text = $result["description"];
        $this->invoice_url = $result["response_text"];

        return true;
        break;
      default:
        $this->invoice_url = "".$result["response_code"];
        $this->status = Pay::FAIL;
        $this->response_code = $result["response_code"];
        $this->response_text = $result["response_text"];
        return false;
        break;
    }
  }
}
