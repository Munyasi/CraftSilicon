<?php
class ControllerPaymentCraftSiliconMpesa extends Controller {
  protected function index() {
    $this->language->load('payment/craft_silicon_mpesa');
    $this->data['button_confirm'] = $this->language->get('button_confirm');
    //$this->data['action'] = 'https://yourpaymentgatewayurl';
  
    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
  //$this->cart->getTotal() ;
    if ($order_info) {
      $this->data['business_no'] = trim($this->config->get('business_no')); 
      $this->data['text_config_two'] = trim($this->config->get('text_config_two')); 
      $this->data['orderid'] = date('His') . $this->session->data['order_id'];
      $this->data['callbackurl'] = $this->url->link('payment/craft_silicon_mpesa/callback&user=Craftsilicon&password=PayT@craftsilicon&statuscode=0&statusmessage=ok&transactionid=0012');
      $this->data['orderdate'] = date('YmdHis');
      $this->data['currency'] = $order_info['currency_code'];
      $this->data['orderamount'] = $this->currency->format($order_info['total'], $this->data['currency'] , false, false);
      $this->data['billemail'] = $order_info['email'];
      $this->data['billphone'] = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
      $this->data['billaddress'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
      $this->data['billcountry'] = html_entity_decode($order_info['payment_iso_code_2'], ENT_QUOTES, 'UTF-8');
      $this->data['billprovince'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');;
      $this->data['billcity'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
      $this->data['billpost'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
      $this->data['deliveryname'] = html_entity_decode($order_info['shipping_firstname'] . $order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
      $this->data['deliveryaddress'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');
      $this->data['deliverycity'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
      $this->data['deliverycountry'] = html_entity_decode($order_info['shipping_iso_code_2'], ENT_QUOTES, 'UTF-8');
      $this->data['deliveryprovince'] = html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8');
      $this->data['deliveryemail'] = $order_info['email'];
      $this->data['deliveryphone'] = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
      $this->data['deliverypost'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
  
      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/craft_silicon_mpesa.tpl')){
        $this->template = $this->config->get('config_template') . '/template/payment/craft_silicon_mpesa.tpl';
      } else {
        $this->template = 'default/template/payment/craft_silicon_mpesa.tpl';
      }
  
      $this->render();
    }
  }

  public function mpesa_instructions(){
    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    $this->data['currency'] = $order_info['currency_code'];
    $this->data['orderamount'] = $this->currency->format($order_info['total'], $this->data['currency'] , false, false);
    $this->data['business_no'] = trim($this->config->get('business_no')); 


    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/craft_silicon_mpesa_form.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/payment/craft_silicon_mpesa_form.tpl';
    } else {
      $this->template = 'default/template/payment/craft_silicon_mpesa_form.tpl';
    }
    $this->response->setOutput($this->render());  
  }

  public function process_payment(){
    $this->load->model('payment/craft_silicon_mpesa');
    $this->load->model('checkout/order'); 
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    $this->data['currency'] = $order_info['currency_code'];
    $amount = $this->currency->format($order_info['total'], $this->data['currency'] , false, false);
    $data = array_merge($this->request->post,$this->request->get);
    $order_id=$this->session->data['order_id'];
    $result=$this->model_payment_craft_silicon_mpesa->process_mpesa_payment($data,$order_id,$amount);
    echo json_encode($result);
  }

  public function mpesa_ipn(){
    $this->load->model('payment/craft_silicon_mpesa');
    $data = array_merge($this->request->post,$this->request->get);
    echo $this->model_payment_craft_silicon_mpesa->save_mpesa_ipn_details($data);
  }
  
  public function callback() {
     $this->load->model('payment/craft_silicon');
     $this->load->model('checkout/order');  

     $data = array_merge($this->request->post,$this->request->get);
     $order_id=$this->session->data['order_id'];
     $result=$this->model_payment_craft_silicon->SavePaymentDetails($data,$order_id);

     if($result){
      $this->redirect($this->url->link('checkout/success', '', 'SSL'));
     }
  }
}
?>