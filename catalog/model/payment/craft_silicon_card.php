<?php
class ModelPaymentCraftSiliconCard extends Model {
  public function getMethod($address, $total) {
    $this->load->language('payment/craft_silicon_card');
  
    $method_data = array(
      'code'     => 'craft_silicon_card',
      'title'    => $this->language->get('text_title'),
      'sort_order' => $this->config->get('custom_sort_order')
    );
  
    return $method_data;
  }

  public function SavePaymentDetails($data,$order_id){
  	if($data['statuscode']==0){
  		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id = ".(int)$order_id);
  		if($query->num_rows>0){
  			$result=$this->db->query("UPDATE " . DB_PREFIX . "order SET payment_code = '" . $data['transactionid'] . "', order_status_id = '" . 2 . "' WHERE order_id = ".(int)$order_id);
  			if($result){
  				$this->cart->clear();
  				return true;
  			}
  			
  		}else{

  			var_dump($data);
  		}
  		
  	}
  }

}