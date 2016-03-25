  <div style="padding: 20px;" id="mobile-payment-form" class="payment-option-form" style="display: block;">
     <div style="background-color: #C5C1C1; padding: 20px; margin-bottom: 20px;" class="instruction_summary">
        <div id="instruction_summary"><b>Send to M-PESA <b>Ksh. <?php echo $orderamount; ?></b> </b> to Pay Bill Business number <b><?php echo $business_no; ?><b>. Submit the <b>Confirmation Code</b> below.</div>
        <div id="instruction_details">
           <ol>
              <li>Go to M-PESA on your phone</li>
              <li>Select Pay Bill option</li>
              <li>Enter Business no. <b><?php echo $business_no; ?></b></li>
              <li>Enter Account no. <b><?php echo $this->session->data["order_id"]; ?></b></li>
              <li>Enter the Amount <b>Ksh. <?php echo $orderamount; ?></b></li>
              <li>Enter your M-PESA PIN and Send</li>
              <li>You will receive a confirmation SMS from M-PESA with a Confirmation Code</li>
              <li>After you receive the confirmation SMS, enter your phone number and the Confirmation Code</li>
              <li>Click on Complete</li>
           </ol>
        </div>
     </div>
     <fieldset>
        <legend>Enter Payment Details</legend>
        <div class="paymentForm">
          <div id="mpesa-error" class="payment-option-form" style="visibility: hidden; display: none;padding:8px">
             <p> </p>
          </div>
           <div style="padding: 5px;line-height: 2em;" class="control-group">
              <label for="MobileNumber" id="MobileNumberHelp" class="control-label">M-Pesa mobile number:</label> 
              <div class="controls"> <input class="countrycode field-sm flfld" disabled="true" id="MobileNumberCountryCodeDisabled" name="MobileNumberCountryCodeDisabled" type="text" value="+254"> <input class="phonenumber" id="MobileNumber" name="MobileNumber" type="text" value=""> <b class="field-validation-valid" data-valmsg-for="MobileNumber" data-valmsg-replace="false">*</b> </div>
           </div>
           <div style="padding: 5px;line-height: 2em;" class="control-group">
              <label for="TransactionCode" id="TransactionCodeName" class="control-label">M-Pesa Confirmation Code:</label> 
              <div class="controls"> <input id="TransactionCode" name="TransactionCode" type="text" value=""> <b class="field-validation-valid" data-valmsg-for="TransactionCode" data-valmsg-replace="false">*</b> </div>
           </div>
        </div>
     </fieldset>
  </div>
  <div class="control-group">
     <div style="margin-left: 25px;" class="controls"> <input type="submit" name="submitButton" value="Complete" class="btn btn-primary btn-medium apisubmit"></div>
  </div>

  <script type="text/javascript">
    $(document).on('click','.apisubmit',function(){
        phone=$('#MobileNumber').val();
        code=$('#TransactionCode').val();
        if(phone.length==10&&code.length>4){
          $.ajax({
            url: 'index.php?route=payment/craft_silicon_mpesa/process_payment',
            dataType: 'json',
            type: 'post',
            data:{
              phone:phone,
              code:code
            },
            beforeSend: function() {
              $('#mpesa-error').css({'visibility':'hidden','display':'none'});
              $('.apisubmit').attr('disabled', true);
              $('.apisubmit').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },    
            complete: function() {
              $('.apisubmit').attr('disabled', false);
              $('.wait').remove();
            },      
            success: function(data) {
              if(data.hasOwnProperty('failed')){
                $('#mpesa-error').html(data.failed.msg);
                $('#mpesa-error').css({'visibility':'visible','display':'block','color':'#ffffff','background-color':'orange'});
              }else if(data.hasOwnProperty('success')){
                $('#mpesa-error').html(data.success.msg);
                $('#mpesa-error').css({'visibility':'visible','display':'block','color':'#ffffff','background-color':'green'});
                 window.location.href="<?php echo $this->url->link('checkout/success', '', 'SSL') ?>";
              }else{
                $('#mpesa-error').html(data.failed.msg);
                $('#mpesa-error').css({'visibility':'visible','display':'block','color':'#ffffff','background-color':'orange'});
              }
            },
            error: function(xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
          });
        }else{
          $('#mpesa-error').html('Error: Wrong Phone Number or Transaction Code entered!')
          $('#mpesa-error').css({'visibility':'visible','display':'block','color':'#ffffff','background-color':'orange'});
        }
    });
  </script>