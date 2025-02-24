<?php
class ControllerPaymentSkrill extends Controller {
	public function index() {
		$this->load->model('checkout/order');

		$this->load->language('payment/skrill');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['action'] = 'https://www.moneybookers.com/app/payment.pl?p=OpenCart';

		$data['pay_to_email'] = $this->config->get('skrill_email');
		$data['platform'] = '31974336';
		$data['description'] = $this->config->get('config_name');
		$data['transaction_id'] = $this->session->data['order_id'];
		$data['return_url'] = $this->url->link('checkout/success');
		$data['cancel_url'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['status_url'] = $this->url->link('payment/skrill/callback');
		$data['language'] = $this->session->data['language'];
		$data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['pay_from_email'] = $order_info['email'];
		$data['firstname'] = $order_info['payment_firstname'];
		$data['lastname'] = $order_info['payment_lastname'];
		$data['address'] = $order_info['payment_address_1'];
		$data['address2'] = $order_info['payment_address_2'];
		$data['phone_number'] = $order_info['telephone'];
		$data['postal_code'] = $order_info['payment_postcode'];
		$data['city'] = $order_info['payment_city'];
		$data['state'] = $order_info['payment_zone'];
		$data['country'] = $order_info['payment_iso_code_3'];
		$data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['currency'] = $order_info['currency_code'];

		$products = '';

		foreach ($this->cart->getProducts() as $product) {
			$products .= $product['quantity'] . ' x ' . $product['name'] . ', ';
		}

		$data['detail1_text'] = $products;

		$data['order_id'] = $this->session->data['order_id'];

		if (file_exists(JPATH_ROOT.'/templates/'.MijoShop::getTmpl().'/html/com_mijoshop/payment/skrill.tpl')) {
            $this->response->setOutput($this->load->view('/templates/'.MijoShop::getTmpl().'/html/com_mijoshop/payment/skrill.tpl', $data));
        }
        else if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/skrill.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/skrill.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/skrill.tpl', $data);
		}
	}

	public function callback() {
		if (isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'));

			$verified = true;

			// md5sig validation
			if ($this->config->get('skrill_secret')) {
				$hash  = $this->request->post['merchant_id'];
				$hash .= $this->request->post['transaction_id'];
				$hash .= strtoupper(md5($this->config->get('skrill_secret')));
				$hash .= $this->request->post['mb_amount'];
				$hash .= $this->request->post['mb_currency'];
				$hash .= $this->request->post['status'];

				$md5hash = strtoupper(md5($hash));
				$md5sig = $this->request->post['md5sig'];

				if ($md5hash != $md5sig) {
					$verified = false;
				}
			}

			if ($verified) {
				switch($this->request->post['status']) {
					case '2':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('skrill_order_status_id'), '', true);
						break;
					case '0':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('skrill_pending_status_id'), '', true);
						break;
					case '-1':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('skrill_canceled_status_id'), '', true);
						break;
					case '-2':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('skrill_failed_status_id'), '', true);
						break;
					case '-3':
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('skrill_chargeback_status_id'), '', true);
						break;
				}
			} else {
				$this->log->write('md5sig returned (' + $md5sig + ') does not match generated (' + $md5hash + '). Verify Manually. Current order state: ' . $this->config->get('config_order_status_id'));
			}
		}
	}
}