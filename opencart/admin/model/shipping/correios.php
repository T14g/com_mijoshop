<?php
class ModelShippingCorreios extends Model {

	public function editTabela($codigo, $data) {
		if(!empty($codigo)) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "correios_servico WHERE codigo = '" . $this->db->escape($codigo) . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "correios_tabela_regiao WHERE codigo = '" . $this->db->escape($codigo) . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "correios_tabela WHERE codigo = '" . $this->db->escape($codigo) . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "correios_faixa_cep_local WHERE codigo = '" . $this->db->escape($codigo) . "'");	
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "correios_servico SET codigo = '" . $this->db->escape($codigo) . "', max_peso_real = '" . (float)$data['max_peso_real'] . "', ad_valorem = '" . (float)$data['ad_valorem'] . "', aviso_recebimento = '" . (float)$data['aviso_recebimento'] . "', mao_propria = '" . (float)$data['mao_propria'] . "', a_cobrar_vpne = '" . (float)$data['a_cobrar_vpne'] . "', taxa_emergencial = '" . (float)$data['taxa_emergencial'] . "', lx_adicional_kg = '" . (float)$data['lx_adicional_kg'] . "', ex_adicional_kg = '" . (float)$data['ex_adicional_kg'] . "', i1_adicional_kg = '" . (float)$data['i1_adicional_kg'] . "', i2_adicional_kg = '" . (float)$data['i2_adicional_kg'] . "', i3_adicional_kg = '" . (float)$data['i3_adicional_kg'] . "', i4_adicional_kg = '" . (float)$data['i4_adicional_kg'] . "', i5_adicional_kg = '" . (float)$data['i5_adicional_kg'] . "', i6_adicional_kg = '" . (float)$data['i6_adicional_kg'] . "', n1_adicional_kg = '" . (float)$data['n1_adicional_kg'] . "', n2_adicional_kg = '" . (float)$data['n2_adicional_kg'] . "', n3_adicional_kg = '" . (float)$data['n3_adicional_kg'] . "', n4_adicional_kg = '" . (float)$data['n4_adicional_kg'] . "', n5_adicional_kg = '" . (float)$data['n5_adicional_kg'] . "', n6_adicional_kg = '" . (float)$data['n6_adicional_kg'] . "'");
			
			if (isset($data['tabela_regiao'])) {
				foreach ($data['tabela_regiao'] as $regiao => $zones) {
					foreach ($zones as $zone_id) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "correios_tabela_regiao SET codigo = '" . $this->db->escape($codigo) . "', regiao = '" .  $this->db->escape($regiao). "', zone_id = '" .  (int)$zone_id . "'");
					}
				}
			}

			if (isset($data['faixa_peso'])) {
				foreach ($data['faixa_peso'] as $faixa) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "correios_tabela SET codigo = '" . $this->db->escape($codigo) . "', peso_inicial = '" . (float)$faixa['peso_inicial'] . "', peso_final = '" . (float)$faixa['peso_final'] . "', lx = '" . (float)$faixa['lx'] . "', ex = '" . (float)$faixa['ex'] . "', i1 = '" . (float)$faixa['i1'] . "', i2 = '" . (float)$faixa['i2'] . "', i3 = '" . (float)$faixa['i3'] . "', i4 = '" . (float)$faixa['i4'] . "', i5 = '" . (float)$faixa['i5'] . "', i6 = '" . (float)$faixa['i6'] . "', n1 = '" . (float)$faixa['n1'] . "', n2 = '" . (float)$faixa['n2'] . "', n3 = '" . (float)$faixa['n3'] . "', n4 = '" . (float)$faixa['n4'] . "', n5 = '" . (float)$faixa['n5'] . "', n6 = '" . (float)$faixa['n6'] . "'");
				}
			}	

			if (isset($data['faixa_cep_local'])) {
				foreach ($data['faixa_cep_local'] as $faixa) {
					$ceps = explode("*", $faixa);
					
					if(count($ceps) == 2) {
						$cep_inicial = preg_replace ("/[^0-9]/", '', $ceps[0]);
						$cep_final = preg_replace ("/[^0-9]/", '', $ceps[1]);
						
						$this->db->query("INSERT INTO " . DB_PREFIX . "correios_faixa_cep_local SET codigo = '" . $this->db->escape($codigo) . "', 	cep_inicio = '" . $this->db->escape($cep_inicial) . "', cep_fim = '" . $this->db->escape($cep_final) . "'");
					}
				}
			}			
		}
	}

	public function getServico($codigo) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "correios_servico WHERE codigo = '" . $this->db->escape($codigo) . "'");

		return $query->row;		
	}
	
	public function getFaixaPeso($codigo) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "correios_tabela WHERE codigo = '" . $this->db->escape($codigo) . "' ORDER BY peso_inicial ASC");

		return $query->rows;		
	}		
	
	public function getTabelaRegiao($codigo) {
		$regiao_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "correios_tabela_regiao WHERE codigo = '" . $this->db->escape($codigo) . "' ORDER BY zone_id ASC");

		foreach ($query->rows as $result) {
			$regiao_data[$result['regiao']][] = $result['zone_id'];
		}

		return $regiao_data;		
	}
	
	public function getFaixasCepLocal($codigo) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "correios_faixa_cep_local WHERE codigo = '" . $this->db->escape($codigo) . "' ORDER BY cep_inicio ASC");

		return $query->rows;		
	}	
	
	public function deleteServicos($data) {
		$codigos = array();
		
		foreach ($data as $servico) {
			$codigos[] = $servico['codigo'];
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "correios_servico WHERE codigo NOT IN ('" . implode("','", $codigos) . "')");
		$this->db->query("DELETE FROM " . DB_PREFIX . "correios_tabela_regiao WHERE codigo NOT IN ('" . implode("','", $codigos) . "')");
		$this->db->query("DELETE FROM " . DB_PREFIX . "correios_tabela WHERE codigo NOT IN ('" . implode("','", $codigos) . "')");
		$this->db->query("DELETE FROM " . DB_PREFIX . "correios_faixa_cep_local WHERE codigo NOT IN ('" . implode("','", $codigos) . "')");
	}	

	public function getZones($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$this->config->get('config_country_id') . "' AND status = '1'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND (name LIKE '" . $this->db->escape($data['filter_name']) . "%' OR code LIKE '" . strtoupper($this->db->escape($data['filter_name'])) . "')";
		}

		$sql .= " ORDER BY name ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
