<?php
if(version_compare(VERSION, '2.2.0.0', '>=')){
	
class ModelTotalDesconto5 extends Model {
	public function getTotal($total) {

		if ($this->config->get('desconto5_status') && isset($this->session->data['payment_method']['code'])) {
			
			$this->language->load('total/credit');
		 
			$total_pedido = isset($total['total'])?$total['total']:$total;
			$regras = $this->config->get('desconto5_descontos');
			
			$meio = $this->session->data['payment_method']['code'];
			
			if(isset($regras[$meio]) && $total_pedido>0){
				//desconto
				if((int)$regras[$meio]['tipo']==0 && (float)$regras[$meio]['taxa']>0){
					
					$credit = ($total_pedido/100)*$regras[$meio]['taxa'];
					$total['totals'][] = array(
						'code'       => 'desconto5',
						'title'      => "Desconto de ".$regras[$meio]['taxa']."%",
						'value'      => -$credit,
						'sort_order' => $this->config->get('desconto5_sort_order')
					);

					$total['total'] -= $credit;
				
				//taxa
				}elseif((int)$regras[$meio]['tipo']==1 && (float)$regras[$meio]['taxa']>0){
					
					$credit = ($total_pedido/100)*$regras[$meio]['taxa'];
					$total['totals'][] = array(
						'code'       => 'desconto5',
						'title'      => "Taxa de ".$regras[$meio]['taxa']."%",
						'value'      => +$credit,
						'sort_order' => $this->config->get('desconto5_sort_order')
					);
					$total['total'] += $credit;
					
				}
			}
		}
	}
}
	
}else{
	
class ModelTotalDesconto5 extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {

		if ($this->config->get('desconto5_status') && isset($this->session->data['payment_method']['code'])) {
			
			$this->language->load('total/credit');
		 
			$total_pedido = $total;//$this->cart->getSubTotal();
			$regras = $this->config->get('desconto5_descontos');
			
			$meio = $this->session->data['payment_method']['code'];
			
			if(isset($regras[$meio]) && $total_pedido>0){
				//desconto
				if((int)$regras[$meio]['tipo']==0 && (float)$regras[$meio]['taxa']>0){
					
					$credit = ($total_pedido/100)*$regras[$meio]['taxa'];
					$total_data[] = array(
						'code'       => 'desconto5',
						'title'      => "Desconto de ".$regras[$meio]['taxa']."%",
						'text'       => $this->currency->format(-$credit),
						'value'      => -$credit,
						'sort_order' => $this->config->get('desconto5_sort_order')
					);
					$total -= $credit;
				
				//taxa
				}elseif((int)$regras[$meio]['tipo']==1 && (float)$regras[$meio]['taxa']>0){
					
					$credit = ($total_pedido/100)*$regras[$meio]['taxa'];
					$total_data[] = array(
						'code'       => 'desconto5',
						'title'      => "Taxa de ".$regras[$meio]['taxa']."%",
						'text'       => $this->currency->format(+$credit),
						'value'      => +$credit,
						'sort_order' => $this->config->get('desconto5_sort_order')
					);
					$total += $credit;
					
				}
			}
		}
	}	
}

}
?>