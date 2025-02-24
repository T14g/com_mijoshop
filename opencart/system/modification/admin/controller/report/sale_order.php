<?php
class ControllerReportSaleOrder extends Controller {
	public function index() {
		$this->load->language('report/sale_order');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}


        #mijoshop-start
        if (isset($this->request->get['filter_payment_code'])) {
            $filter_payment_code = $this->request->get['filter_payment_code'];
        } else {
            $filter_payment_code = '';
        }
		#mijoshop-finish
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}


        #mijoshop-start
        if (isset($this->request->get['filter_payment_code'])) {
            $url .= '&filter_payment_code=' . $this->request->get['filter_payment_code'];
        }
		#mijoshop-finish
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/sale');

		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_group'           => $filter_group,
			'filter_order_status_id' => $filter_order_status_id,
'filter_payment_code'    => $filter_payment_code,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_sale->getTotalOrders($filter_data);

		$results = $this->model_report_sale->getOrders($filter_data);

		foreach ($results as $result) {
			$data['orders'][] = array(
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'orders'     => $result['orders'],
				'products'   => $result['products'],
				'tax'        => $this->currency->format($result['tax'], $this->config->get('config_currency')),
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');
$data['text_all_payment'] = $this->language->get('text_all_payment');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');
$data['entry_payment'] = $this->language->get('entry_payment');

		$data['button_filter'] = $this->language->get('button_filter');
        $data['button_output'] = $this->language->get('button_output');
        $data['button_export'] = $this->language->get('button_export');

        $graph = array(
            'sales' => array(
                'model'            => 'sale',
                'function'         => 'orders',
                'title'            => $this->language->get('text_sale'),
                'link'             => str_replace('&amp;', '&', $this->url->link('report/graph/graph', 'tmpl=component&format=raw&token=' . $this->session->data['token'] . '&' . http_build_query($filter_data) . '&page=' . $page, 'SSL')),
                'color'            => '#008db9',
                'background-color' => '#FFFFFF',
                'total'            => 'total',
                'price'            => true
            ),
            'orders'   => array(
                'model'            => 'sale',
                'function'         => 'orders',
                'title'            => $this->language->get('text_order'),
                'link'             => str_replace('&amp;', '&', $this->url->link('report/graph/graph', 'tmpl=component&format=raw&token=' . $this->session->data['token'] . '&' . http_build_query($filter_data) . '&page=' . $page, 'SSL')),
                'color'            => '#5cb85c',
                'background-color' => '#FFFFFF',
                'total'            => 'orders',
                'price'            => false
            ),
            'products' => array(
                'model'            => 'sale',
                'function'         => 'orders',
                'title'            => $this->language->get('text_product'),
                'link'             => str_replace('&amp;', '&', $this->url->link('report/graph/graph', 'tmpl=component&format=raw&token=' . $this->session->data['token'] . '&' . http_build_query($filter_data) . '&page=' . $page, 'SSL')),
                'color'            => '#d9534f',
                'background-color' => '#FFFFFF',
                'total'            => 'products',
                'price'            => false
            )
        );

        $data['graph'] = $this->load->controller('report/graph', $graph);

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        #mijoshop-start
        $this->load->model('extension/extension');

        $payment_methods = $this->model_extension_extension->getExtensions(array('filter_type' => 'payment'));

        $data['payment_methods'] = array();

        foreach ($payment_methods as $payment_method) {
            $this->load->language('payment/' . $payment_method['code']);

            $data['payment_methods'][] = array(
                'code'  => $payment_method['code'],
                'title' => $this->language->get('heading_title'),
            );
        }
        #mijoshop-finish


		$data['groups'] = array();

		$data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}


        if (isset($this->request->get['filter_payment_code'])) {
            $url .= '&filter_payment_code=' . $this->request->get['filter_payment_code'];
        }

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_order_status_id'] = $filter_order_status_id;
$data['filter_payment_code'] = $filter_payment_code;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_order.tpl', $data));
	}
}