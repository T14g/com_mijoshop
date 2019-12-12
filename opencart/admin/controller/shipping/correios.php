<?php
class ControllerShippingCorreios extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/correios');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('shipping/correios');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('correios', $this->request->post);	
			$this->model_shipping_correios->deleteServicos($this->request->post['correios_servicos']);
			
			// somente redireciona se o Submit não tenha sido feito para editar a tabela offline
			if(!isset($this->request->get['codigo'])) {				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		$data['correios_version'] = 'v4.11 - 07/12/2018';
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_shipping'] = $this->language->get('text_shipping');	
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_todos'] = $this->language->get('text_todos');
		$data['text_nenhum'] = $this->language->get('text_nenhum');
		$data['text_autocomplete'] = $this->language->get('text_autocomplete');
		$data['text_servicos'] = $this->language->get('text_servicos');
		$data['text_restricoes'] = $this->language->get('text_restricoes');
		$data['text_frete_gratis'] = $this->language->get('text_frete_gratis');
		$data['text_gratis_estado'] = $this->language->get('text_gratis_estado');
		$data['text_gratis_produto'] = $this->language->get('text_gratis_produto');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_automatico'] = $this->language->get('text_automatico');	
		$data['text_online'] = $this->language->get('text_online');	
		$data['text_offline'] = $this->language->get('text_offline');
		$data['text_faixa_ceps'] = $this->language->get('text_faixa_ceps');

		$data['tab_servico'] = $this->language->get('tab_servico');
		$data['tab_restricao'] = $this->language->get('tab_restricao');	
		$data['tab_frete_gratis'] = $this->language->get('tab_frete_gratis');	
		$data['tab_gratis_estado'] = $this->language->get('tab_gratis_estado');	
		$data['tab_gratis_produto'] = $this->language->get('tab_gratis_produto');
		$data['tab_gratis_cep'] = $this->language->get('tab_gratis_cep');

		$data['entry_servicos'] = $this->language->get('entry_servicos');
		$data['entry_codigo'] = $this->language->get('entry_codigo');
		$data['entry_nome'] = $this->language->get('entry_nome');
		$data['entry_a_cobrar'] = $this->language->get('entry_a_cobrar');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_contrato_codigo'] = $this->language->get('entry_contrato_codigo');
		$data['entry_contrato_senha'] = $this->language->get('entry_contrato_senha');
		$data['entry_max_declarado'] = $this->language->get('entry_max_declarado');
		$data['entry_min_declarado'] = $this->language->get('entry_min_declarado');
		$data['entry_max_soma_lados'] = $this->language->get('entry_max_soma_lados');
		$data['entry_min_soma_lados'] = $this->language->get('entry_min_soma_lados');
		$data['entry_max_lado']= $this->language->get('entry_max_lado');
		$data['entry_max_peso']= $this->language->get('entry_max_peso');
		$data['entry_mao_propria']= $this->language->get('entry_mao_propria');
		$data['entry_aviso_recebimento']= $this->language->get('entry_aviso_recebimento');
		$data['entry_declarar_valor']= $this->language->get('entry_declarar_valor');	
		$data['entry_total']= $this->language->get('entry_total');	
		$data['entry_prazo_adicional']= $this->language->get('entry_prazo_adicional');	
		$data['entry_adicional']= $this->language->get('entry_adicional');	
		$data['entry_status']= $this->language->get('entry_status');	
		$data['entry_tax_class']= $this->language->get('entry_tax_class');	
		$data['entry_geo_zone']= $this->language->get('entry_geo_zone');	
		$data['entry_sort_order']= $this->language->get('entry_sort_order');
		$data['entry_estados_gratis']= $this->language->get('entry_estados_gratis');
		$data['entry_restricoes']= $this->language->get('entry_restricoes');
		$data['entry_cep_inicial']= $this->language->get('entry_cep_inicial');
		$data['entry_cep_final']= $this->language->get('entry_cep_final');
		$data['entry_descricao']= $this->language->get('entry_descricao');
		$data['entry_estado']= $this->language->get('entry_estado');
		$data['entry_produto']= $this->language->get('entry_produto');
		$data['entry_categoria']= $this->language->get('entry_categoria');
		$data['entry_marca']= $this->language->get('entry_marca');
		$data['entry_adicional_manuseio_especial']= $this->language->get('entry_adicional_manuseio_especial');
		$data['entry_operacao']= $this->language->get('entry_operacao');
		$data['entry_tabela']= $this->language->get('entry_tabela');
		$data['entry_msg_restricao']= $this->language->get('entry_msg_restricao');
		
		$data['help_codigo'] = $this->language->get('help_codigo');
		$data['help_nome'] = $this->language->get('help_nome');
		$data['help_a_cobrar'] = $this->language->get('help_a_cobrar');
		$data['help_postcode'] = $this->language->get('help_postcode');
		$data['help_contrato_codigo'] = $this->language->get('help_contrato_codigo');
		$data['help_contrato_senha'] = $this->language->get('help_contrato_senha');
		$data['help_max_declarado'] = $this->language->get('help_max_declarado');
		$data['help_min_declarado'] = $this->language->get('help_min_declarado');
		$data['help_max_soma_lados'] = $this->language->get('help_max_soma_lados');
		$data['help_min_soma_lados'] = $this->language->get('help_min_soma_lados');
		$data['help_max_lado'] = $this->language->get('help_max_lado');
		$data['help_max_peso'] = $this->language->get('help_max_peso');
		$data['help_mao_propria'] = $this->language->get('help_mao_propria');
		$data['help_aviso_recebimento'] = $this->language->get('help_aviso_recebimento');
		$data['help_declarar_valor'] = $this->language->get('help_declarar_valor');
		$data['help_total'] = $this->language->get('help_total');
		$data['help_prazo_adicional'] = $this->language->get('help_prazo_adicional');
		$data['help_adicional'] = $this->language->get('help_adicional');
		$data['help_estados_gratis'] = $this->language->get('help_estados_gratis');	
		$data['help_cep_inicial'] = $this->language->get('help_cep_inicial');	
		$data['help_cep_final'] = $this->language->get('help_cep_final');
		$data['help_adicional_manuseio_especial']= $this->language->get('help_adicional_manuseio_especial');
		$data['help_operacao'] = $this->language->get('help_operacao');
		$data['help_tabela'] = $this->language->get('help_tabela');	
		$data['help_msg_restricao'] = $this->language->get('help_msg_restricao');	
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_add_servico'] = $this->language->get('button_add_servico');
		$data['button_add_restricao'] = $this->language->get('button_add_restricao');
		$data['button_tabela'] = $this->language->get('button_tabela');		
		
		$data['tab_general'] = $this->language->get('tab_general');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['required'])) {
			$data['error_warning'] = $this->error['required'];
		} else {
			$data['error_warning'] = '';
		}		

		$data['breadcrumbs'] = array();
   		
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
   		);
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/correios', 'token=' . $this->session->data['token'], 'SSL')
   		);
		
   		$data['action'] = $this->url->link('shipping/correios', 'token=' . $this->session->data['token'], 'SSL');
		
   		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['correios_servicos'])) {
			$data['correios_servicos'] = $this->request->post['correios_servicos'];
		} else {
			$data['correios_servicos'] = $this->config->get('correios_servicos');
		}
		
		if(empty($data['correios_servicos'])) {
			$data['correios_servicos'] = array();
		}
		
		if (isset($this->request->post['correios_faixas'])) {
			$data['correios_faixas'] = $this->request->post['correios_faixas'];
		} else {
			$data['correios_faixas'] = $this->config->get('correios_faixas');
		}
		
		if(empty($data['correios_faixas'])) {
			$data['correios_faixas'] = array();
		}

		if (isset($this->request->post['correios_gratis_estados'])) {
			$data['correios_gratis_estados'] = $this->request->post['correios_gratis_estados'];
		} else {
			$data['correios_gratis_estados'] = $this->config->get('correios_gratis_estados');
		}
		
		if(empty($data['correios_gratis_estados'])) {
			$data['correios_gratis_estados'] = array();
		}	

		if (isset($this->request->post['correios_gratis_produtos'])) {
			$data['correios_gratis_produtos'] = $this->request->post['correios_gratis_produtos'];
		} else {
			$data['correios_gratis_produtos'] = $this->config->get('correios_gratis_produtos');
		}
		
		$data['product_categories'] = array();
		$data['product_manufacturers'] = array();
		$data['product_products'] = array();
		
		if(empty($data['correios_gratis_produtos'])) {
			$data['correios_gratis_produtos'] = array();
		} else {
			$this->load->model('catalog/category');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/product');
			
			foreach($data['correios_gratis_produtos'] as $key => $row) {
				
				if (isset($row['categorias']) && is_array($row['categorias'])) {
					foreach ($row['categorias'] as $category_id) {
						$category_info = $this->model_catalog_category->getCategory($category_id);

						if ($category_info) {
							$data['product_categories'][$key][] = array(
								'category_id' => $category_info['category_id'],
								'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
							);
						}
					}
				}
				
				if (isset($row['marcas']) && is_array($row['marcas'])) {
					foreach ($row['marcas'] as $manufacturer_id) {
						$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

						if ($manufacturer_info) {
							$data['product_manufacturers'][$key][] = array(
								'manufacturer_id' 	=> $manufacturer_info['manufacturer_id'],
								'name'        		=> $manufacturer_info['name']
							);
						}
					}
				}

				if (isset($row['produtos']) && is_array($row['produtos'])) {
					foreach ($row['produtos'] as $product_id) {
						$product_info = $this->model_catalog_product->getProduct($product_id);

						if ($product_info) {
							$data['product_products'][$key][] = array(
								'product_id'=> $product_info['product_id'],
								'name'      => $product_info['name']
							);
						}
					}
				}				
			}			
		}

		if (isset($this->request->post['correios_gratis_cep'])) {
			$data['correios_gratis_cep'] = $this->request->post['correios_gratis_cep'];
		} else {
			$data['correios_gratis_cep'] = $this->config->get('correios_gratis_cep');
		}
		
		if(empty($data['correios_gratis_cep'])) {
			$data['correios_gratis_cep'] = array();
		}

		if (isset($this->request->post['correios_msg_restricao'])) {
			$data['correios_msg_restricao'] = $this->request->post['correios_msg_restricao'];
		} else {
			$data['correios_msg_restricao'] = $this->config->get('correios_msg_restricao');
		}		

		if (isset($this->request->post['correios_status'])) {
			$data['correios_status'] = $this->request->post['correios_status'];
		} else {
			$data['correios_status'] = $this->config->get('correios_status');
		}
		
		if (isset($this->request->post['correios_tax_class_id'])) {
			$data['correios_tax_class_id'] = $this->request->post['correios_tax_class_id'];
		} else {
			$data['correios_tax_class_id'] = $this->config->get('correios_tax_class_id');
		}
		
		if (isset($this->request->post['correios_geo_zone_id'])) {
			$data['correios_geo_zone_id'] = $this->request->post['correios_geo_zone_id'];
		} else {
			$data['correios_geo_zone_id'] = $this->config->get('correios_geo_zone_id');
		}
		
		if (isset($this->request->post['correios_sort_order'])) {
			$data['correios_sort_order'] = $this->request->post['correios_sort_order'];
		} else {
			$data['correios_sort_order'] = $this->config->get('correios_sort_order');
		}
		
		$this->load->model('localisation/tax_class');
		
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$this->load->model('localisation/zone');
		
		$data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->config->get('config_country_id'));

		$data['token'] = $this->session->data['token'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		if (version_compare(VERSION, '2.2') < 0) {
			$this->response->setOutput($this->load->view('shipping/correios.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('shipping/correios', $data));
		}
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/correios')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!empty($this->request->post['correios_servicos'])) {
			foreach ($this->request->post['correios_servicos'] as $servico) {
				if ((utf8_strlen($servico['codigo']) == 0) || (utf8_strlen($servico['nome']) == 0) || (utf8_strlen($servico['postcode']) == 0) || (utf8_strlen($servico['max_declarado']) == 0) || (utf8_strlen($servico['min_declarado']) == 0) || (utf8_strlen($servico['max_soma_lados']) == 0) || (utf8_strlen($servico['min_soma_lados']) == 0) || (utf8_strlen($servico['max_lado']) == 0) || (utf8_strlen($servico['max_peso']) == 0)) {
					$this->error['required'] = $this->language->get('error_required');
					break;
				}
			}
		}		

		return !$this->error;
	}
	
	public function tabela() {
		$this->load->language('shipping/correios_tabela');

		$this->document->setTitle($this->language->get('heading_title_tabela'));
		
		if(isset($this->request->get['codigo'])) {
			$codigo = $this->request->get['codigo'];
		} else {
			$codigo = '';
		}

		$this->load->model('shipping/correios');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_shipping_correios->editTabela($codigo, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('shipping/correios', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getTabelaForm();
	}		

	public function getTabelaForm() {
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_title_tabela'] = $this->language->get('heading_title_tabela');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_shipping'] = $this->language->get('text_shipping');			
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_faixa_peso'] = $this->language->get('text_faixa_peso');
		$data['text_ix'] = $this->language->get('text_ix');
		$data['text_nx'] = $this->language->get('text_nx');
		$data['text_autocomplete'] = $this->language->get('text_autocomplete');
		$data['text_cep_local_inicio'] = $this->language->get('text_cep_local_inicio');
		$data['text_cep_local_fim'] = $this->language->get('text_cep_local_fim');
		
		$data['entry_max_peso_real'] = $this->language->get('entry_max_peso_real');
		$data['entry_ad_valorem'] = $this->language->get('entry_ad_valorem');
		$data['entry_aviso_recebimento'] = $this->language->get('entry_aviso_recebimento');
		$data['entry_mao_propria'] = $this->language->get('entry_mao_propria');
		$data['entry_a_cobrar_vpne'] = $this->language->get('entry_a_cobrar_vpne');
		$data['entry_taxa_emergencial'] = $this->language->get('entry_taxa_emergencial');
		
		$data['entry_peso_inicial'] = $this->language->get('entry_peso_inicial');
		$data['entry_peso_final'] = $this->language->get('entry_peso_final');
		$data['entry_adicional_kg'] = $this->language->get('entry_adicional_kg');
		$data['entry_lx'] = $this->language->get('entry_lx');
		$data['entry_ex'] = $this->language->get('entry_ex');
		$data['entry_i1'] = $this->language->get('entry_i1');
		$data['entry_i2'] = $this->language->get('entry_i2');
		$data['entry_i3'] = $this->language->get('entry_i3');
		$data['entry_i4'] = $this->language->get('entry_i4');
		$data['entry_i5'] = $this->language->get('entry_i5');
		$data['entry_i6'] = $this->language->get('entry_i6');
		$data['entry_n1'] = $this->language->get('entry_n1');
		$data['entry_n2'] = $this->language->get('entry_n2');
		$data['entry_n3'] = $this->language->get('entry_n3');
		$data['entry_n4'] = $this->language->get('entry_n4');
		$data['entry_n5'] = $this->language->get('entry_n5');
		$data['entry_n6'] = $this->language->get('entry_n6');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');		
		$data['button_add_faixa_peso'] = $this->language->get('button_add_faixa_peso');
		$data['button_add_faixa_cep_local'] = $this->language->get('button_add_faixa_cep_local');
		
		$data['help_max_peso_real'] = $this->language->get('help_max_peso_real');
		$data['help_ad_valorem'] = $this->language->get('help_ad_valorem');
		$data['help_aviso_recebimento'] = $this->language->get('help_aviso_recebimento');
		$data['help_mao_propria'] = $this->language->get('help_mao_propria');
		$data['help_a_cobrar_vpne'] = $this->language->get('help_a_cobrar_vpne');
		$data['help_taxa_emergencial'] = $this->language->get('help_taxa_emergencial');
		$data['help_faixa_peso'] = $this->language->get('help_faixa_peso');
		$data['help_ix'] = $this->language->get('help_ix');
		$data['help_nx'] = $this->language->get('help_nx');
		$data['help_lx'] = $this->language->get('help_lx');
		$data['help_ex'] = $this->language->get('help_ex');
		$data['error_permission'] = $this->language->get('error_permission');
		$data['error_required'] = $this->language->get('error_required');
		
		if(isset($this->request->get['codigo'])) {
			$codigo = $this->request->get['codigo'];
		} else {
			$codigo = '';
		}

		$data['text_form'] = sprintf($this->language->get('text_edit'), $codigo);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();
   		
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);
		
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
   		);
		
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/correios', 'token=' . $this->session->data['token'], 'SSL')
   		);	
		
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_tabela'),
			'href' => $this->url->link('shipping/correios/tabela', 'token=' . $this->session->data['token'] . '&codigo=' . $codigo, 'SSL')
   		);
		
		$this->load->model('shipping/correios');
		
		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$tabela_info = $this->model_shipping_correios->getServico($codigo);
		}
		
		$data['action'] = $this->url->link('shipping/correios/tabela', 'token=' . $this->session->data['token'] . '&codigo=' . $codigo, 'SSL');
		
		$data['cancel'] = $this->url->link('shipping/correios', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['max_peso_real'])) {
			$data['max_peso_real'] = $this->request->post['max_peso_real'];
		} elseif (!empty($tabela_info)) {
			$data['max_peso_real'] = $tabela_info['max_peso_real'];
		} else {
			$data['max_peso_real'] = '';
		}

		if (isset($this->request->post['ad_valorem'])) {
			$data['ad_valorem'] = $this->request->post['ad_valorem'];
		} elseif (!empty($tabela_info)) {
			$data['ad_valorem'] = $tabela_info['ad_valorem'];
		} else {
			$data['ad_valorem'] = '';
		}

		if (isset($this->request->post['aviso_recebimento'])) {
			$data['aviso_recebimento'] = $this->request->post['aviso_recebimento'];
		} elseif (!empty($tabela_info)) {
			$data['aviso_recebimento'] = $tabela_info['aviso_recebimento'];
		} else {
			$data['aviso_recebimento'] = '';
		}

		if (isset($this->request->post['mao_propria'])) {
			$data['mao_propria'] = $this->request->post['mao_propria'];
		} elseif (!empty($tabela_info)) {
			$data['mao_propria'] = $tabela_info['mao_propria'];
		} else {
			$data['mao_propria'] = '';
		}

		if (isset($this->request->post['a_cobrar_vpne'])) {
			$data['a_cobrar_vpne'] = $this->request->post['a_cobrar_vpne'];
		} elseif (!empty($tabela_info)) {
			$data['a_cobrar_vpne'] = $tabela_info['a_cobrar_vpne'];
		} else {
			$data['a_cobrar_vpne'] = '';
		}

		if (isset($this->request->post['taxa_emergencial'])) {
			$data['taxa_emergencial'] = $this->request->post['taxa_emergencial'];
		} elseif (!empty($tabela_info)) {
			$data['taxa_emergencial'] = $tabela_info['taxa_emergencial'];
		} else {
			$data['taxa_emergencial'] = '';
		}

		if (isset($this->request->post['lx_adicional_kg'])) {
			$data['lx_adicional_kg'] = $this->request->post['lx_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['lx_adicional_kg'] = $tabela_info['lx_adicional_kg'];
		} else {
			$data['lx_adicional_kg'] = '';
		}

		if (isset($this->request->post['ex_adicional_kg'])) {
			$data['ex_adicional_kg'] = $this->request->post['ex_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['ex_adicional_kg'] = $tabela_info['ex_adicional_kg'];
		} else {
			$data['ex_adicional_kg'] = '';
		}

		if (isset($this->request->post['i1_adicional_kg'])) {
			$data['i1_adicional_kg'] = $this->request->post['i1_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['i1_adicional_kg'] = $tabela_info['i1_adicional_kg'];
		} else {
			$data['i1_adicional_kg'] = '';
		}	

		if (isset($this->request->post['i2_adicional_kg'])) {
			$data['i2_adicional_kg'] = $this->request->post['i2_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['i2_adicional_kg'] = $tabela_info['i2_adicional_kg'];
		} else {
			$data['i2_adicional_kg'] = '';
		}	

		if (isset($this->request->post['i3_adicional_kg'])) {
			$data['i3_adicional_kg'] = $this->request->post['i3_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['i3_adicional_kg'] = $tabela_info['i3_adicional_kg'];
		} else {
			$data['i3_adicional_kg'] = '';
		}	

		if (isset($this->request->post['i4_adicional_kg'])) {
			$data['i4_adicional_kg'] = $this->request->post['i4_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['i4_adicional_kg'] = $tabela_info['i4_adicional_kg'];
		} else {
			$data['i4_adicional_kg'] = '';
		}

		if (isset($this->request->post['i5_adicional_kg'])) {
			$data['i5_adicional_kg'] = $this->request->post['i5_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['i5_adicional_kg'] = $tabela_info['i5_adicional_kg'];
		} else {
			$data['i5_adicional_kg'] = '';
		}	

		if (isset($this->request->post['i6_adicional_kg'])) {
			$data['i6_adicional_kg'] = $this->request->post['i6_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['i6_adicional_kg'] = $tabela_info['i6_adicional_kg'];
		} else {
			$data['i6_adicional_kg'] = '';
		}

		if (isset($this->request->post['n1_adicional_kg'])) {
			$data['n1_adicional_kg'] = $this->request->post['n1_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['n1_adicional_kg'] = $tabela_info['n1_adicional_kg'];
		} else {
			$data['n1_adicional_kg'] = '';
		}	

		if (isset($this->request->post['n2_adicional_kg'])) {
			$data['n2_adicional_kg'] = $this->request->post['n2_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['n2_adicional_kg'] = $tabela_info['n2_adicional_kg'];
		} else {
			$data['n2_adicional_kg'] = '';
		}	

		if (isset($this->request->post['n3_adicional_kg'])) {
			$data['n3_adicional_kg'] = $this->request->post['n3_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['n3_adicional_kg'] = $tabela_info['n3_adicional_kg'];
		} else {
			$data['n3_adicional_kg'] = '';
		}	

		if (isset($this->request->post['n4_adicional_kg'])) {
			$data['n4_adicional_kg'] = $this->request->post['n4_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['n4_adicional_kg'] = $tabela_info['n4_adicional_kg'];
		} else {
			$data['n4_adicional_kg'] = '';
		}

		if (isset($this->request->post['n5_adicional_kg'])) {
			$data['n5_adicional_kg'] = $this->request->post['n5_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['n5_adicional_kg'] = $tabela_info['n5_adicional_kg'];
		} else {
			$data['n5_adicional_kg'] = '';
		}	

		if (isset($this->request->post['n6_adicional_kg'])) {
			$data['n6_adicional_kg'] = $this->request->post['n6_adicional_kg'];
		} elseif (!empty($tabela_info)) {
			$data['n6_adicional_kg'] = $tabela_info['n6_adicional_kg'];
		} else {
			$data['n6_adicional_kg'] = '';
		}		

		if (isset($this->request->post['faixa_peso'])) {
			$data['faixa_peso'] = $this->request->post['faixa_peso'];
		} elseif (!empty($tabela_info)) {
			$data['faixa_peso'] = $this->model_shipping_correios->getFaixaPeso($tabela_info['codigo']);
		} else {
			$data['faixa_peso'] = array();
		}

		if (isset($this->request->post['tabela_regiao'])) {
			$tabela_regiao = $this->request->post['tabela_regiao'];
		} elseif (!empty($tabela_info)) {
			$tabela_regiao = $this->model_shipping_correios->getTabelaRegiao($tabela_info['codigo']);
		} else {
			$tabela_regiao = array();
		}

		$this->load->model('localisation/zone');

		$data['tabela_regiao'] = array();

		foreach ($tabela_regiao as $regiao => $zones) {
			foreach ($zones as $zone_id) {
				$zone_info = $this->model_localisation_zone->getZone($zone_id);

				if ($zone_info) {
					$data['tabela_regiao'][$regiao][] = array(
						'zone_id' => $zone_info['zone_id'],
						'code'    => $zone_info['code']
					);
				}
			}
		}

		if (isset($this->request->post['faixa_cep_local'])) {
			$data['faixa_cep_local'] = $this->request->post['faixa_cep_local'];
		} elseif (!empty($tabela_info)) {
			$data['faixa_cep_local'] = $this->model_shipping_correios->getFaixasCepLocal($tabela_info['codigo']);
		} else {
			$data['faixa_cep_local'] = array();
		}		
		
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		if (version_compare(VERSION, '2.2') < 0) {
			$this->response->setOutput($this->load->view('shipping/correios_tabela_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('shipping/correios_tabela_form', $data));
		}		
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'shipping/correios')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}	

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('shipping/correios');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_shipping_correios->getZones($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'zone_id' => $result['zone_id'],
					'name'    => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'code' 	  => $result['code']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {

		$result = $this->db->query("SELECT count(*) as total FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '" . DB_DATABASE . "') AND (TABLE_NAME = '" . DB_PREFIX . "correios_servico')");
		
		if(!$result->row['total']) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correios_faixa_cep_local` (
			  `faixa_cep_local_id` int(11) NOT NULL AUTO_INCREMENT,
			  `codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
			  `cep_inicio` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
			  `cep_fim` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`faixa_cep_local_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correios_servico` (
			  `servico_id` int(11) NOT NULL AUTO_INCREMENT,
			  `codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
			  `max_peso_real` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `ad_valorem` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `aviso_recebimento` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `mao_propria` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `a_cobrar_vpne` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `taxa_emergencial` decimal(13,2) NOT NULL,
			  `lx_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `ex_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i1_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i2_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i3_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i4_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i5_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i6_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n1_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n2_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n3_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n4_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n5_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n6_adicional_kg` decimal(13,2) NOT NULL DEFAULT '0.00',
			  PRIMARY KEY (`servico_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "correios_servico` (`servico_id`, `codigo`, `max_peso_real`, `ad_valorem`, `aviso_recebimento`, `mao_propria`, `a_cobrar_vpne`, `taxa_emergencial`, `lx_adicional_kg`, `ex_adicional_kg`, `i1_adicional_kg`, `i2_adicional_kg`, `i3_adicional_kg`, `i4_adicional_kg`, `i5_adicional_kg`, `i6_adicional_kg`, `n1_adicional_kg`, `n2_adicional_kg`, `n3_adicional_kg`, `n4_adicional_kg`, `n5_adicional_kg`, `n6_adicional_kg`) VALUES
				(NULL, '04510', '10.00', '1.50', '5.00', '5.90', '0.00', '3.00', '3.90', '4.60', '8.90', '10.00', '12.80', '15.90', '18.60', '23.50', '5.30', '6.10', '7.00', '8.60', '10.60', '13.20');");

			$this->db->query("INSERT INTO `" . DB_PREFIX . "correios_servico` (`servico_id`, `codigo`, `max_peso_real`, `ad_valorem`, `aviso_recebimento`, `mao_propria`, `a_cobrar_vpne`, `taxa_emergencial`, `lx_adicional_kg`, `ex_adicional_kg`, `i1_adicional_kg`, `i2_adicional_kg`, `i3_adicional_kg`, `i4_adicional_kg`, `i5_adicional_kg`, `i6_adicional_kg`, `n1_adicional_kg`, `n2_adicional_kg`, `n3_adicional_kg`, `n4_adicional_kg`, `n5_adicional_kg`, `n6_adicional_kg`) VALUES
				(NULL, '04014', '10.00', '1.50', '5.00', '5.90', '0.00', '3.00', '3.90', '4.60', '8.90', '10.00', '12.80', '15.90', '18.60', '23.50', '5.30', '6.10', '7.00', '8.60', '10.60', '13.20');");				
				
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correios_tabela` (
			  `tabela_id` int(11) NOT NULL AUTO_INCREMENT,
			  `codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
			  `peso_inicial` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `peso_final` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `lx` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `ex` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i1` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i2` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i3` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i4` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i5` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `i6` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n1` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n2` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n3` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n4` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n5` decimal(13,2) NOT NULL DEFAULT '0.00',
			  `n6` decimal(13,2) NOT NULL DEFAULT '0.00',
			  PRIMARY KEY (`tabela_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "correios_tabela` (`tabela_id`, `codigo`, `peso_inicial`, `peso_final`, `lx`, `ex`, `i1`, `i2`, `i3`, `i4`, `i5`, `i6`, `n1`, `n2`, `n3`, `n4`, `n5`, `n6`) VALUES
				(NULL, '04510', '6001.00', '7000.00', '27.00', '32.10', '52.80', '60.60', '82.00', '105.20', '124.70', '160.60', '36.00', '41.40', '47.20', '58.40', '71.90', '89.90'),
				(NULL, '04510', '5001.00', '6000.00', '25.40', '30.40', '49.40', '56.70', '77.50', '99.70', '117.90', '152.20', '32.60', '37.50', '42.70', '52.90', '65.10', '81.40'),
				(NULL, '04510', '4001.00', '5000.00', '22.80', '27.50', '42.60', '48.60', '68.00', '87.30', '102.10', '131.90', '29.40', '33.00', '36.80', '44.10', '52.90', '64.70'),
				(NULL, '04510', '3001.00', '4000.00', '21.50', '25.40', '40.70', '46.40', '65.60', '84.50', '98.70', '127.70', '27.50', '30.80', '34.40', '41.30', '49.50', '60.50'),
				(NULL, '04510', '2001.00', '3000.00', '20.50', '23.60', '31.80', '37.20', '56.20', '74.60', '88.30', '116.60', '25.80', '28.80', '32.20', '38.60', '46.30', '56.60'),
				(NULL, '04510', '1001.00', '2000.00', '19.10', '21.50', '27.60', '32.50', '50.90', '68.30', '80.80', '107.40', '21.60', '24.10', '26.90', '32.30', '38.80', '47.40'),
				(NULL, '04510', '0.00', '1000.00', '17.40', '19.20', '23.20', '28.00', '46.10', '63.00', '74.90', '100.70', '19.60', '22.00', '24.50', '29.40', '35.30', '43.10'),
				(NULL, '04510', '7001.00', '8000.00', '28.00', '33.90', '68.00', '76.20', '98.20', '122.40', '143.10', '180.70', '39.20', '45.00', '51.40', '63.60', '78.30', '97.90'),
				(NULL, '04510', '8001.00', '9000.00', '29.70', '35.50', '69.90', '78.50', '100.70', '125.60', '147.00', '185.50', '41.40', '47.30', '53.90', '66.80', '82.20', '102.70'),
				(NULL, '04510', '9001.00', '10000.00', '31.30', '37.00', '71.30', '80.00', '102.50', '127.80', '149.80', '188.90', '42.50', '48.80', '55.70', '69.00', '84.90', '106.10');");
				
			$this->db->query("INSERT INTO `" . DB_PREFIX . "correios_tabela` (`tabela_id`, `codigo`, `peso_inicial`, `peso_final`, `lx`, `ex`, `i1`, `i2`, `i3`, `i4`, `i5`, `i6`, `n1`, `n2`, `n3`, `n4`, `n5`, `n6`) VALUES
				(NULL, '04014', '6001.00', '7000.00', '27.00', '32.10', '52.80', '60.60', '82.00', '105.20', '124.70', '160.60', '36.00', '41.40', '47.20', '58.40', '71.90', '89.90'),
				(NULL, '04014', '5001.00', '6000.00', '25.40', '30.40', '49.40', '56.70', '77.50', '99.70', '117.90', '152.20', '32.60', '37.50', '42.70', '52.90', '65.10', '81.40'),
				(NULL, '04014', '4001.00', '5000.00', '22.80', '27.50', '42.60', '48.60', '68.00', '87.30', '102.10', '131.90', '29.40', '33.00', '36.80', '44.10', '52.90', '64.70'),
				(NULL, '04014', '3001.00', '4000.00', '21.50', '25.40', '40.70', '46.40', '65.60', '84.50', '98.70', '127.70', '27.50', '30.80', '34.40', '41.30', '49.50', '60.50'),
				(NULL, '04014', '2001.00', '3000.00', '20.50', '23.60', '31.80', '37.20', '56.20', '74.60', '88.30', '116.60', '25.80', '28.80', '32.20', '38.60', '46.30', '56.60'),
				(NULL, '04014', '1001.00', '2000.00', '19.10', '21.50', '27.60', '32.50', '50.90', '68.30', '80.80', '107.40', '21.60', '24.10', '26.90', '32.30', '38.80', '47.40'),
				(NULL, '04014', '0.00', '1000.00', '17.40', '19.20', '23.20', '28.00', '46.10', '63.00', '74.90', '100.70', '19.60', '22.00', '24.50', '29.40', '35.30', '43.10'),
				(NULL, '04014', '7001.00', '8000.00', '28.00', '33.90', '68.00', '76.20', '98.20', '122.40', '143.10', '180.70', '39.20', '45.00', '51.40', '63.60', '78.30', '97.90'),
				(NULL, '04014', '8001.00', '9000.00', '29.70', '35.50', '69.90', '78.50', '100.70', '125.60', '147.00', '185.50', '41.40', '47.30', '53.90', '66.80', '82.20', '102.70'),
				(NULL, '04014', '9001.00', '10000.00', '31.30', '37.00', '71.30', '80.00', '102.50', '127.80', '149.80', '188.90', '42.50', '48.80', '55.70', '69.00', '84.90', '106.10');");				
			
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correios_tabela_regiao` (
			  `tabela_regiao_id` int(11) NOT NULL AUTO_INCREMENT,
			  `codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
			  `zone_id` int(11) NOT NULL,
			  `regiao` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`tabela_regiao_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "correios_tabela_regiao` (`tabela_regiao_id`, `codigo`, `zone_id`, `regiao`) VALUES
				(NULL, '04510', 462, 'n5'),
				(NULL, '04510', 440, 'n5'),
				(NULL, '04510', 465, 'n4'),
				(NULL, '04510', 461, 'n4'),
				(NULL, '04510', 459, 'n4'),
				(NULL, '04510', 457, 'n4'),
				(NULL, '04510', 454, 'n4'),
				(NULL, '04510', 449, 'n4'),
				(NULL, '04510', 443, 'n4'),
				(NULL, '04510', 442, 'n4'),
				(NULL, '04510', 441, 'n4'),
				(NULL, '04510', 466, 'n3'),
				(NULL, '04510', 456, 'n3'),
				(NULL, '04510', 453, 'n3'),
				(NULL, '04510', 445, 'n3'),
				(NULL, '04510', 450, 'n2'),
				(NULL, '04510', 444, 'n2'),
				(NULL, '04510', 463, 'n1'),
				(NULL, '04510', 460, 'n1'),
				(NULL, '04510', 458, 'n1'),
				(NULL, '04510', 455, 'n1'),
				(NULL, '04510', 452, 'n1'),
				(NULL, '04510', 451, 'n1'),
				(NULL, '04510', 448, 'n1'),
				(NULL, '04510', 447, 'n1'),
				(NULL, '04510', 446, 'n1'),
				(NULL, '04510', 462, 'i5'),
				(NULL, '04510', 440, 'i5'),
				(NULL, '04510', 465, 'i4'),
				(NULL, '04510', 461, 'i4'),
				(NULL, '04510', 459, 'i4'),
				(NULL, '04510', 457, 'i4'),
				(NULL, '04510', 454, 'i4'),
				(NULL, '04510', 449, 'i4'),
				(NULL, '04510', 443, 'i4'),
				(NULL, '04510', 442, 'i4'),
				(NULL, '04510', 441, 'i4'),
				(NULL, '04510', 466, 'i3'),
				(NULL, '04510', 456, 'i3'),
				(NULL, '04510', 453, 'i3'),
				(NULL, '04510', 445, 'i3'),
				(NULL, '04510', 450, 'i2'),
				(NULL, '04510', 444, 'i2'),
				(NULL, '04510', 463, 'i1'),
				(NULL, '04510', 460, 'i1'),
				(NULL, '04510', 458, 'i1'),
				(NULL, '04510', 455, 'i1'),
				(NULL, '04510', 452, 'i1'),
				(NULL, '04510', 451, 'i1'),
				(NULL, '04510', 448, 'i1'),
				(NULL, '04510', 447, 'i1'),
				(NULL, '04510', 446, 'i1');");
				
			$this->db->query("INSERT INTO `" . DB_PREFIX . "correios_tabela_regiao` (`tabela_regiao_id`, `codigo`, `zone_id`, `regiao`) VALUES
				(NULL, '04014', 462, 'n5'),
				(NULL, '04014', 440, 'n5'),
				(NULL, '04014', 465, 'n4'),
				(NULL, '04014', 461, 'n4'),
				(NULL, '04014', 459, 'n4'),
				(NULL, '04014', 457, 'n4'),
				(NULL, '04014', 454, 'n4'),
				(NULL, '04014', 449, 'n4'),
				(NULL, '04014', 443, 'n4'),
				(NULL, '04014', 442, 'n4'),
				(NULL, '04014', 441, 'n4'),
				(NULL, '04014', 466, 'n3'),
				(NULL, '04014', 456, 'n3'),
				(NULL, '04014', 453, 'n3'),
				(NULL, '04014', 445, 'n3'),
				(NULL, '04014', 450, 'n2'),
				(NULL, '04014', 444, 'n2'),
				(NULL, '04014', 463, 'n1'),
				(NULL, '04014', 460, 'n1'),
				(NULL, '04014', 458, 'n1'),
				(NULL, '04014', 455, 'n1'),
				(NULL, '04014', 452, 'n1'),
				(NULL, '04014', 451, 'n1'),
				(NULL, '04014', 448, 'n1'),
				(NULL, '04014', 447, 'n1'),
				(NULL, '04014', 446, 'n1'),
				(NULL, '04014', 462, 'i5'),
				(NULL, '04014', 440, 'i5'),
				(NULL, '04014', 465, 'i4'),
				(NULL, '04014', 461, 'i4'),
				(NULL, '04014', 459, 'i4'),
				(NULL, '04014', 457, 'i4'),
				(NULL, '04014', 454, 'i4'),
				(NULL, '04014', 449, 'i4'),
				(NULL, '04014', 443, 'i4'),
				(NULL, '04014', 442, 'i4'),
				(NULL, '04014', 441, 'i4'),
				(NULL, '04014', 466, 'i3'),
				(NULL, '04014', 456, 'i3'),
				(NULL, '04014', 453, 'i3'),
				(NULL, '04014', 445, 'i3'),
				(NULL, '04014', 450, 'i2'),
				(NULL, '04014', 444, 'i2'),
				(NULL, '04014', 463, 'i1'),
				(NULL, '04014', 460, 'i1'),
				(NULL, '04014', 458, 'i1'),
				(NULL, '04014', 455, 'i1'),
				(NULL, '04014', 452, 'i1'),
				(NULL, '04014', 451, 'i1'),
				(NULL, '04014', 448, 'i1'),
				(NULL, '04014', 447, 'i1'),
				(NULL, '04014', 446, 'i1');");			
		}
	}
}
?>
