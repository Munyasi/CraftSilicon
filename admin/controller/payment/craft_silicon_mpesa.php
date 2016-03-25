<?php
class ControllerPaymentCraftSiliconMpesa extends Controller {
  private $error = array();
 
  public function index() {
    $this->language->load('payment/craft_silicon_mpesa');
    $this->document->setTitle('Craft Silicon MPESA Configuration');
    $this->load->model('setting/setting');
 
    if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      $this->model_setting_setting->editSetting('craft_silicon_mpesa', $this->request->post);
      $this->session->data['success'] = 'Saved.';
      $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }
 
    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['business_no_text'] = $this->language->get('business_no_text');
    $this->data['entry_text_config_two'] = $this->language->get('text_config_two');
    $this->data['button_save'] = $this->language->get('text_button_save');
    $this->data['button_cancel'] = $this->language->get('text_button_cancel');
    $this->data['entry_order_status'] = $this->language->get('entry_order_status');
    $this->data['text_enabled'] = $this->language->get('text_enabled');
    $this->data['text_disabled'] = $this->language->get('text_disabled');
    $this->data['entry_status'] = $this->language->get('entry_status');
 
    $this->data['action'] = $this->url->link('payment/craft_silicon_mpesa', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
 
    if (isset($this->request->post['business_no'])) {
      $this->data['business_no'] = $this->request->post['business_no'];
    } else {
      $this->data['business_no'] = $this->config->get('business_no');
    }
        
    if (isset($this->request->post['text_config_two'])) {
      $this->data['text_config_two'] = $this->request->post['text_config_two'];
    } else {
      $this->data['text_config_two'] = $this->config->get('text_config_two');
    }
            
    if (isset($this->request->post['custom_status'])) {
      $this->data['custom_status'] = $this->request->post['custom_status'];
    } else {
      $this->data['custom_status'] = $this->config->get('custom_status');
    }
        
    if (isset($this->request->post['custom_order_status_id'])) {
      $this->data['custom_order_status_id'] = $this->request->post['custom_order_status_id'];
    } else {
      $this->data['custom_order_status_id'] = $this->config->get('custom_order_status_id');
    }
 
    $this->load->model('localisation/order_status');
    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    $this->template = 'payment/craft_silicon_mpesa.tpl';
            
    $this->children = array(
      'common/header',
      'common/footer'
    );
 
    $this->response->setOutput($this->render());
  }
}