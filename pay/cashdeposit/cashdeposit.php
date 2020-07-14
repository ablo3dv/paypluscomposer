<?php
class Pay_Cashdeposit extends Pay_Checkout {

  protected $amount = 0.0;
  protected $currency = "fcfa";
  protected $customer = "";
  protected $custom_data;
  protected $setup;
  protected $utility;

  function __construct(Pay_Setup $setup, $customer = "", $amount = 0.0){
    $this->custom_data = new Pay_CustomData();
    $this->setup = new Pay_Setup();
    $this->setup->insert($setup);
    $this->utility = new Pay_Utilities($setup);
    $this->setAmount($amount);
    $this->setCustomer($customer);
  }

  public function setAmount($amount) {
    $this->amount = round($amount,2);
  }

  public function getAmount(){
    return $this->amount;
  }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

  public function getStatus() {
    return $this->status;
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


  public function confirm($token="") {
    $mtoken = trim($token);
    $result = $this->utility->httpGetRequest($this->setup->getWithdrawalConfirmUrl()."?cashdepositToken=" . $mtoken);
    if(count($result) > 0) {
      switch ($result['status']) {
        case 'completed':
          $this->status = $result['status'];
            $this->response_text = "Deposit status is ".strtoupper($result['status']);
            $this->operator_id = "".$result['operator_id'];
          return true;
          break;
        default:
          $this->status = $result['status'];
          $this->response_text = "Deposit status is ".strtoupper($result['status']);
          return false;
      }
    }else{
      $this->status = Pay::FAIL;
      $this->response_code = 1002;
      $this->response_text = "Withdrawal Not Found";
      return false;
    }
  }

  public function create() {
      $withdrawal_payload = array(
          'customer' => $this->getCustomer(),
          'amount' => $this->getAmount(),
          'custom_data' => $this->showCustomData()
      );

      //echo var_dump($withdrawal_payload)."<br><br>";
      //echo $this->setup->getWithdrawalBaseUrl();
      //exit();

      $result = $this->utility->httpJsonRequest($this->setup->getWithdrawalBaseUrl(),$withdrawal_payload);

      //echo var_dump($result)."<br><br>";
      //echo $this->setup->getWithdrawalBaseUrl();
      //exit();

      switch ($result["response_code"]) {
          case "00":
              $this->status = Pay::SUCCESS;
              $this->token = $result["token"];
              $this->response_code = $result["response_code"];
              $this->response_text = $result["description"];
              return true;
              break;
          default:
              $this->status = Pay::FAIL;
              $this->response_code = $result["response_code"];
              $this->response_text = $result["response_text"];
              return false;
              break;
      }
  }

}
