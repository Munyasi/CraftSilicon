
<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="text_config_one" value="<?php echo $text_config_one; ?>" />
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
  
  <div class="buttons">
    <div class="right">
      <input type="submit" class="button_confirm" id="button-confirm" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>


<script type="text/javascript">
  $(document).ready(function(){
    $(document.body).on('click','.button_confirm',function(e){
      e.preventDefault();
      if($('input[name=payment_method]:checked').val()=='craft_silicon_card'){

        html='<iframe id="visa_master_container" style="width: 100%;height:100%;min-height: 400px;" src="http://196.43.248.80:5000/VISAclient/MakePayment.aspx?MerchantID=M3RC4NTaXe1t&Token=9875648796875&Currency=KES&Amount=<?php echo number_format($orderamount)."00" ?>&Details=Iphone6&MerchantReff=<?php echo "PT".$orderid; ?>&ReturnUrl=<?php echo $callbackurl ?>"></iframe>';
        $('#payment-form .checkout-content').html(html);
        $('#confirm .checkout-content').slideUp('slow');
        $('#payment-form .checkout-content').slideDown('slow');
      }
      
    })
  });
</script>
