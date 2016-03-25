<?php if($_SERVER['REQUEST_METHOD'] == 'POST'&&isset($_POST['m-submit'])): ?>
  
<?php else: ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?route=checkout/checkout" method="post">
  <input type="hidden" name="business_no" value="<?php echo $business_no; ?>" />
  <input type="hidden" name="text_config_two" value="<?php echo $text_config_two; ?>" />
  <input type="hidden" name="orderid" value="<?php echo $orderid; ?>" />
  <input type="hidden" name="callbackurl" value="<?php echo $callbackurl; ?>" />
  <input type="hidden" name="orderdate" value="<?php echo $orderdate; ?>" />
  <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input type="hidden" name="orderamount" value="<?php echo $orderamount; ?>" />
  <input type="hidden" name="billemail" value="<?php echo $billemail; ?>" />
  <input type="hidden" name="billphone" value="<?php echo $billphone; ?>" />
  <input type="hidden" name="billaddress" value="<?php echo $billaddress; ?>" />
  <input type="hidden" name="billcountry" value="<?php echo $billcountry; ?>" />
  <input type="hidden" name="billprovince" value="<?php echo $billprovince; ?>" />
  <input type="hidden" name="billcity" value="<?php echo $billcity; ?>" />
  <input type="hidden" name="billpost" value="<?php echo $billpost; ?>" />
  <input type="hidden" name="deliveryname" value="<?php echo $deliveryname; ?>" />
  <input type="hidden" name="deliveryaddress" value="<?php echo $deliveryaddress; ?>" />
  <input type="hidden" name="deliverycity" value="<?php echo $deliverycity; ?>" />
  <input type="hidden" name="deliverycountry" value="<?php echo $deliverycountry; ?>" />
  <input type="hidden" name="deliveryprovince" value="<?php echo $deliveryprovince; ?>" />
  <input type="hidden" name="deliveryemail" value="<?php echo $deliveryemail; ?>" />
  <input type="hidden" name="deliveryphone" value="<?php echo $deliveryphone; ?>" />
  <input type="hidden" name="deliverypost" value="<?php echo $deliverypost; ?>" />
  <?php $account_number="PT".$this->session->data["order_id"]; ?>
  <div class="buttons">
    <div class="right">
      <input type="submit" class="button_confirm" id="button-confirm" name="m-submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>
<?php endif; ?>

<style type="text/css">
  span{
    font-size: bold;

  }
</style>
<script type="text/javascript">
  $(document).ready(function(){
    $(document.body).on('click','.button_confirm',function(e){
      e.preventDefault();
      if($('input[name=payment_method]:checked').val()=='craft_silicon_mpesa'){
        $.ajax({
          url: 'index.php?route=payment/craft_silicon_mpesa/mpesa_instructions',
          dataType: 'html',
          success: function(html) {
            $('#payment-form .checkout-content').html(html);
            $('#confirm .checkout-content').slideUp('slow');
            $('#payment-form .checkout-content').slideDown('slow');
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        }); 
      }
      
     
    })

   
  });
</script>
