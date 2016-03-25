<?php
class ModelPaymentCraftSiliconMpesa extends Model {
  public function getMethod($address, $total) {
    $this->load->language('payment/craft_silicon_mpesa');
  
    $method_data = array(
      'code'     => 'craft_silicon_mpesa',
      'title'    => $this->language->get('text_title'),
      'sort_order' => $this->config->get('custom_sort_order')
    );
  
    return $method_data;
  }

  public function save_mpesa_ipn_details($data){
    $result_obj=array();
    try {
      $this->write_to_file($data);
    } catch (Exception $e) {
      $result_obj['failed']=array('file_error'=>'file_error');
    }


    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mpesa_ipn WHERE receipt_number = ".$this->db->escape($data['receipt_number']));
    if($query->num_rows==0){
        $result=$this->db->query("INSERT INTO " . DB_PREFIX . "mpesa_ipn SET receipt_number = '" . $this->db->escape($data['receipt_number']) . "', amount='" . $this->db->escape($data['amount']) . "',account_number='" . $this->db->escape($data['account_number']) . "',mpesa_sender='" . $this->db->escape($data['mpesa_sender']) . "',ip_address='" . $this->db->escape($this->getIpAddress()). "'");
        if($result){
          $result_obj['success']=array('ok'=>'notification received');
        }else{
          $result_obj['failed']=array('db_error'=>'database error');
        }
        
      }else{
      $result_obj['failed']=array('duplicate'=>'Record already exixts');
    }

    return json_encode($result_obj);

  }

  function getIpAddress(){
    if (isset($_SERVER['HTTP_CLIENT_IP'])&&trim($_SERVER['HTTP_CLIENT_IP'])!="")   //checking ip address from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])&&trim($_SERVER['HTTP_X_FORWARDED_FOR'])!="")   //checking whether ip address is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

  public function write_to_file($data){
    $myFile = "mpesalog.txt";
    $fh = fopen($myFile, 'a') or die("can't open file");
    fwrite($fh, "=============================\n");

    foreach ($data as $var => $value) {
      fwrite($fh, "$var = $value\n");
    }
    fclose($fh);
  }

  public function process_mpesa_payment($data,$order_id,$amount){
   $result_obj=array();
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mpesa_ipn WHERE receipt_number = '".$this->db->escape($data['code'])."' AND mpesa_sender='".$this->db->escape($data['phone']) . "' AND status='" . 0 ."' ORDER BY id DESC LIMIT 1");
    if($query->num_rows>0){
      /*check for insufficient amount*/
      /*remember to trim phone number*/

      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id = ".(int)$order_id." ORDER BY order_id DESC LIMIT 1");
      if($query->num_rows>0){
        $result=$this->db->query("UPDATE " . DB_PREFIX . "order SET payment_code = '" . $data['code'] . "', order_status_id = '" . 2 . "' WHERE order_id = ".(int)$order_id." ORDER BY order_id DESC LIMIT 1");
        if($result){
          $result2=$this->db->query("UPDATE " . DB_PREFIX . "mpesa_ipn SET status = '" . 1 . "' WHERE receipt_number = ".$data['code']." ORDER BY id DESC LIMIT 1");
          $this->cart->clear();
           $result_obj['success']=array('msg'=>'Success');
        }
      }else{
        $result_obj['failed']=array('msg'=>'Your order is invalid');
      }
    }else{
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mpesa_ipn WHERE receipt_number = '".$this->db->escape($data['code'])."' AND mpesa_sender='".$this->db->escape($data['phone']) . "' AND status='" . 1 ."' ORDER BY id DESC LIMIT 1");
      if($query->num_rows>0){
        $result_obj['failed']=array('msg'=>'Code Already Used');
      }else{
         $result_obj['failed']=array('msg'=>'Your MPESA code or Phone number is invalid');
      }
    }
     return $result_obj;
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