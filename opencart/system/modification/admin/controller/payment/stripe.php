<?php
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class ControllerPaymentStripe extends Controller
{
    protected $error = array();

    public function install()
    {
        if ($this->user->hasPermission('modify', 'extension/payment')) {
            $this->load->model('payment/stripe');

            $this->model_payment_stripe->install();
        }
    }

    public function uninstall()
    {
        if ($this->user->hasPermission('modify', 'extension/payment')) {
            $this->load->model('payment/stripe');

            $this->model_payment_stripe->uninstall();
        }
    }

    public function index()
    {
        $this->load->language('payment/stripe');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('stripe', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link($this->request->get['route'], 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'] . '&filter_type=payment', true));
        }

        #Get All Language Text
        $data = $this->language->all();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['currencies'] = ['usd', 'eur'];

        if ($this->initStripe() == true) {
            $data['currencies'] = \Stripe\CountrySpec::retrieve("US")['supported_payment_currencies'];
        }

        $data['action'] = $this->url->link('payment/stripe', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'] . '&type=payment', true);

        if (isset($this->request->post['stripe_test_publishable_key'])) {
            $data['stripe_test_publishable_key'] = $this->request->post['stripe_test_publishable_key'];
        } elseif ($this->config->has('stripe_test_publishable_key')) {
            $data['stripe_test_publishable_key'] = $this->config->get('stripe_test_publishable_key');
        } else {
            $data['stripe_test_publishable_key'] = '';
        }

        if (isset($this->request->post['stripe_test_secret_key'])) {
            $data['stripe_test_secret_key'] = $this->request->post['stripe_test_secret_key'];
        } elseif ($this->config->has('stripe_test_secret_key')) {
            $data['stripe_test_secret_key'] = $this->config->get('stripe_test_secret_key');
        } else {
            $data['stripe_test_secret_key'] = '';
        }

        if (isset($this->request->post['stripe_live_publishable_key'])) {
            $data['stripe_live_publishable_key'] = $this->request->post['stripe_live_publishable_key'];
        } elseif ($this->config->has('stripe_live_publishable_key')) {
            $data['stripe_live_publishable_key'] = $this->config->get('stripe_live_publishable_key');
        } else {
            $data['stripe_live_publishable_key'] = '';
        }

        if (isset($this->request->post['stripe_live_secret_key'])) {
            $data['stripe_live_secret_key'] = $this->request->post['stripe_live_secret_key'];
        } elseif ($this->config->has('stripe_live_secret_key')) {
            $data['stripe_live_secret_key'] = $this->config->get('stripe_live_secret_key');
        } else {
            $data['stripe_live_secret_key'] = '';
        }

        if (isset($this->request->post['stripe_environment'])) {
            $data['stripe_environment'] = $this->request->post['stripe_environment'];
        } elseif ($this->config->has('stripe_environment')) {
            $data['stripe_environment'] = $this->config->get('stripe_environment');
        } else {
            $data['stripe_environment'] = 'test';
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['stripe_order_status_id'])) {
            $data['stripe_order_status_id'] = $this->request->post['stripe_order_status_id'];
        } else {
            $data['stripe_order_status_id'] = $this->config->get('stripe_order_status_id');
        }

        if (isset($this->request->post['stripe_currency'])) {
            $data['stripe_currency'] = $this->request->post['stripe_currency'];
        } elseif ($this->config->has('stripe_currency')) {
            $data['stripe_currency'] = $this->config->get('stripe_currency');
        } else {
            $data['stripe_currency'] = 'usd';
        }

        if (isset($this->request->post['stripe_store_cards'])) {
            $data['stripe_store_cards'] = $this->request->post['stripe_store_cards'];
        } elseif ($this->config->has('stripe_store_cards')) {
            $data['stripe_store_cards'] = $this->config->get('stripe_store_cards');
        } else {
            $data['stripe_store_cards'] = 0;
        }

        if (isset($this->request->post['stripe_status'])) {
            $data['stripe_status'] = $this->request->post['stripe_status'];
        } elseif ($this->config->has('stripe_status')) {
            $data['stripe_status'] = $this->config->get('stripe_status');
        } else {
            $data['stripe_status'] = 0;
        }

        if (isset($this->request->post['stripe_sort_order'])) {
            $data['stripe_sort_order'] = $this->request->post['stripe_sort_order'];
        } elseif ($this->config->has('stripe_sort_order')) {
            $data['stripe_sort_order'] = $this->config->get('stripe_sort_order');
        } else {
            $data['stripe_sort_order'] = '';
        }

        $data['token'] = $this->session->data['token'];

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/stripe.tpl', $data));
    }

    public function refund()
    {
        $this->load->language('payment/stripe');

        $this->initStripe();

        $json = array();

        $json['error'] = false;

        if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
            $this->load->model('payment/stripe');
            $this->load->model('user/user');

            $stripe_order = $this->model_payment_stripe->getOrder($this->request->post['order_id']);

            $user_info = $this->model_user_user->getUser($this->user->getId());

            $re = \Stripe\Refund::create(array(
                                             "charge"   => $stripe_order['stripe_order_id'],
                                             "amount"   => $this->request->post['amount'] * 100,
                                             "metadata" => array(
                                                 "opencart_user_username" => $user_info['username'],
                                                 "opencart_user_id"       => $this->user->getId()
                                             )
                                         ));
        } else {
            $json['error'] = true;
            $json['msg']   = 'Missing data';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function order()
    {
        if ($this->config->get('stripe_status')) {
            $this->load->language('payment/stripe');

            #Get All Language Text
            $data = $this->language->all();

            $data['order_id'] = $this->request->get['order_id'];

            $this->load->model('payment/stripe');

            $stripe_order = $this->model_payment_stripe->getOrder($this->request->get['order_id']);

            if ($stripe_order && $this->initStripe()) {
                $data['stripe_environment'] = $stripe_order['environment'];

                $data['charge']      = \Stripe\Charge::retrieve($stripe_order['stripe_order_id']);
                $data['transaction'] = \Stripe\BalanceTransaction::retrieve($data['charge']['balance_transaction']);

                $data['token'] = $this->request->get['token'];

                return $this->load->view('payment/stripe_order.tpl', $data);
            }
        }
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/stripe')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function initStripe()
    {
        $this->load->library('stripe');

        if ($this->config->get('stripe_environment') == 'live') {
            $stripe_secret_key = $this->config->get('stripe_live_secret_key');
        } else {
            $stripe_secret_key = $this->config->get('stripe_test_secret_key');
        }

        if ($stripe_secret_key != '' && $stripe_secret_key != null && (strlen($stripe_secret_key) > 5)) {
            \Stripe\Stripe::setApiKey($stripe_secret_key);

            return true;
        }

        return false;
    }
}
