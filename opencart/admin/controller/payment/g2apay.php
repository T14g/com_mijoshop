<?php
/*
* @package      MijoShop
* @copyright    2009-2016 Miwisoft LLC, miwisoft.com
* @license      GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license      GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class ControllerPaymentG2APay extends Controller
{
    private $error = array();

    public function install()
    {
        $this->load->model('payment/g2apay');

        $this->model_payment_g2apay->install();
    }

    public function uninstall()
    {
        $this->load->model('payment/g2apay');

        $this->model_payment_g2apay->uninstall();
    }

    public function index()
    {
        $this->load->language('payment/g2apay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('g2apay', $this->request->post);

            $this->session->data['complete'] = $this->language->get('text_complete');

            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link($this->request->get['route'], 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data = $this->language->all();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['secret'])) {
            $data['error_secret'] = $this->error['secret'];
        } else {
            $data['error_secret'] = '';
        }

        if (isset($this->error['api_hash'])) {
            $data['error_api_hash'] = $this->error['api_hash'];
        } else {
            $data['error_api_hash'] = '';
        }

        if (isset($this->request->post['g2apay_order_status_id'])) {
            $data['g2apay_order_status_id'] = $this->request->post['g2apay_order_status_id'];
        } else {
            $data['g2apay_order_status_id'] = $this->config->get('g2apay_order_status_id');
        }

        if (isset($this->request->post['g2apay_complete_status_id'])) {
            $data['g2apay_complete_status_id'] = $this->request->post['g2apay_complete_status_id'];
        } else {
            $data['g2apay_complete_status_id'] = $this->config->get('g2apay_complete_status_id');
        }

        if (isset($this->request->post['g2apay_rejected_status_id'])) {
            $data['g2apay_rejected_status_id'] = $this->request->post['g2apay_rejected_status_id'];
        } else {
            $data['g2apay_rejected_status_id'] = $this->config->get('g2apay_rejected_status_id');
        }

        if (isset($this->request->post['g2apay_cancelled_status_id'])) {
            $data['g2apay_cancelled_status_id'] = $this->request->post['g2apay_cancelled_status_id'];
        } else {
            $data['g2apay_cancelled_status_id'] = $this->config->get('g2apay_cancelled_status_id');
        }

        if (isset($this->request->post['g2apay_pending_status_id'])) {
            $data['g2apay_pending_status_id'] = $this->request->post['g2apay_pending_status_id'];
        } else {
            $data['g2apay_pending_status_id'] = $this->config->get('g2apay_pending_status_id');
        }

        if (isset($this->request->post['g2apay_refunded_status_id'])) {
            $data['g2apay_refunded_status_id'] = $this->request->post['g2apay_refunded_status_id'];
        } else {
            $data['g2apay_refunded_status_id'] = $this->config->get('g2apay_refunded_status_id');
        }

        if (isset($this->request->post['g2apay_partially_refunded_status_id'])) {
            $data['g2apay_partially_refunded_status_id'] = $this->request->post['g2apay_partially_refunded_status_id'];
        } else {
            $data['g2apay_partially_refunded_status_id'] = $this->config->get('g2apay_partially_refunded_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['action'] = $this->url->link('payment/g2apay', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);

        if (isset($this->request->post['g2apay_username'])) {
            $data['g2apay_username'] = $this->request->post['g2apay_username'];
        } else {
            $data['g2apay_username'] = $this->config->get('g2apay_username');
        }

        if (isset($this->request->post['g2apay_secret'])) {
            $data['g2apay_secret'] = $this->request->post['g2apay_secret'];
        } else {
            $data['g2apay_secret'] = $this->config->get('g2apay_secret');
        }

        if (isset($this->request->post['g2apay_api_hash'])) {
            $data['g2apay_api_hash'] = $this->request->post['g2apay_api_hash'];
        } else {
            $data['g2apay_api_hash'] = $this->config->get('g2apay_api_hash');
        }

        if (isset($this->request->post['g2apay_environment'])) {
            $data['g2apay_environment'] = $this->request->post['g2apay_environment'];
        } else {
            $data['g2apay_environment'] = $this->config->get('g2apay_environment');
        }

        if (isset($this->request->post['g2apay_total'])) {
            $data['g2apay_total'] = $this->request->post['g2apay_total'];
        } else {
            $data['g2apay_total'] = $this->config->get('g2apay_total');
        }

        if (isset($this->request->post['g2apay_secret_token'])) {
            $data['g2apay_secret_token'] = $this->request->post['g2apay_secret_token'];
        } elseif ($this->config->get('g2apay_secret_token')) {
            $data['g2apay_secret_token'] = $this->config->get('g2apay_secret_token');
        } else {
            $data['g2apay_secret_token'] = sha1(uniqid(mt_rand(), 1));
        }

        $data['g2apay_ipn_url'] = HTTPS_CATALOG . 'index.php?route=payment/g2apay/ipn&token=' . $data['g2apay_secret_token'];

        if (isset($this->request->post['g2apay_ipn_uri'])) {
            $data['g2apay_ipn_uri'] = $this->request->post['g2apay_ipn_uri'];
        } else {
            $data['g2apay_ipn_uri'] = $this->config->get('g2apay_ipn_uri');
        }

        if (isset($this->request->post['g2apay_order_status_id'])) {
            $data['g2apay_order_status_id'] = $this->request->post['g2apay_order_status_id'];
        } else {
            $data['g2apay_order_status_id'] = $this->config->get('g2apay_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['g2apay_geo_zone_id'])) {
            $data['g2apay_geo_zone_id'] = $this->request->post['g2apay_geo_zone_id'];
        } else {
            $data['g2apay_geo_zone_id'] = $this->config->get('g2apay_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['g2apay_status'])) {
            $data['g2apay_status'] = $this->request->post['g2apay_status'];
        } else {
            $data['g2apay_status'] = $this->config->get('g2apay_status');
        }

        if (isset($this->request->post['g2apay_debug'])) {
            $data['g2apay_debug'] = $this->request->post['g2apay_debug'];
        } else {
            $data['g2apay_debug'] = $this->config->get('g2apay_debug');
        }

        if (isset($this->request->post['g2apay_sort_order'])) {
            $data['g2apay_sort_order'] = $this->request->post['g2apay_sort_order'];
        } else {
            $data['g2apay_sort_order'] = $this->config->get('g2apay_sort_order');
        }

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/g2apay.tpl', $data));
    }

    public function order()
    {
        if ($this->config->get('g2apay_status')) {
            $this->load->model('payment/g2apay');

            $g2apay_order = $this->model_payment_g2apay->getOrder($this->request->get['order_id']);

            if (!empty($g2apay_order)) {
                $this->load->language('payment/g2apay');

                $data = $this->language->all();

                $g2apay_order['total_released'] = $this->model_payment_g2apay->getTotalReleased($g2apay_order['g2apay_order_id']);

                $g2apay_order['total_formatted']          = $this->currency->format($g2apay_order['total'], $g2apay_order['currency_code'], false);
                $g2apay_order['total_released_formatted'] = $this->currency->format($g2apay_order['total_released'], $g2apay_order['currency_code'], false);

                $data['g2apay_order'] = $g2apay_order;

                $data['order_id'] = $this->request->get['order_id'];
                $data['token']    = $this->request->get['token'];

                return $this->load->view('payment/g2apay_order.tpl', $data);
            }
        }
    }

    public function refund()
    {
        $this->load->language('payment/g2apay');

        $json = array();

        if (isset($this->request->post['order_id']) && !empty($this->request->post['order_id'])) {
            $this->load->model('payment/g2apay');

            $g2apay_order = $this->model_payment_g2apay->getOrder($this->request->post['order_id']);

            $refund_response = $this->model_payment_g2apay->refund($g2apay_order, $this->request->post['amount']);

            $this->model_payment_g2apay->logger($refund_response);

            if ($refund_response == 'ok') {
                $this->model_payment_g2apay->addTransaction($g2apay_order['g2apay_order_id'], 'refund', $this->request->post['amount'] * -1);

                $total_refunded = $this->model_payment_g2apay->getTotalRefunded($g2apay_order['g2apay_order_id']);
                $total_released = $this->model_payment_g2apay->getTotalReleased($g2apay_order['g2apay_order_id']);

                if ($total_released <= 0 && $g2apay_order['release_status'] == 1) {
                    $this->model_payment_g2apay->updateRefundStatus($g2apay_order['g2apay_order_id'], 1);

                    $refund_status = 1;

                    $json['msg']   = $this->language->get('text_refund_ok_order');
                } else {
                    $refund_status = 0;
                    $json['msg']   = $this->language->get('text_refund_ok');
                }

                $json['data'] = array();

                $json['data']['date_added']     = date("Y-m-d H:i:s");
                $json['data']['amount']         = $this->currency->format(($this->request->post['amount'] * -1), $g2apay_order['currency_code'], false);
                $json['data']['total_released'] = (float) $total_released;
                $json['data']['total_refunded'] = (float) $total_refunded;
                $json['data']['refund_status']  = $refund_status;

                $json['error'] = false;
            } else {
                $json['error'] = true;
                $json['msg']   = 'Unable to refund: ' . $refund_response;
            }
        } else {
            $json['error'] = true;
            $json['msg']   = 'Missing data';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/g2apay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['g2apay_username']) {
            $this->error['username'] = $this->language->get('error_username');
        }

        if (!$this->request->post['g2apay_secret']) {
            $this->error['secret'] = $this->language->get('error_secret');
        }

        if (!$this->request->post['g2apay_api_hash']) {
            $this->error['api_hash'] = $this->language->get('error_api_hash');
        }

        return !$this->error;
    }
}
