<?php
class ControllerModuleCorreiosOffline5 extends Controller {
	public function calcular() {
        @header('Content-type: text/xml');
        if(isset($_GET['nCdServico']) && !empty($_GET['nCdServico'])){
        $url_full = $this->curPageURL();
        $cep = preg_replace('/\D/', '', $_GET['sCepDestino']);
        $regra = $this->config->get('correios_offline5_regra');
        //sempre usar webservice
        if($regra==1){
            $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
            $url .=	"nCdEmpresa=" . $_GET['nCdEmpresa'];
            $url .=	"&sDsSenha=" . $_GET['sDsSenha'];
            $url .=	"&sCepOrigem=".$_GET['sCepOrigem'];
            $url .=	"&sCepDestino=".$cep;
            $url .=	"&nVlPeso=".$_GET['nVlPeso'];
            $url .=	"&nCdFormato=1";
            $url .=	"&nVlComprimento=".$_GET['nVlComprimento'];
            $url .=	"&nVlLargura=".$_GET['nVlLargura'];
            $url .=	"&nVlAltura=".$_GET['nVlAltura'];
            $url .=	"&sCdMaoPropria=".$_GET['sCdMaoPropria'];
            $url .=	"&nVlValorDeclarado=".$_GET['nVlValorDeclarado'];
            $url .=	"&sCdAvisoRecebimento=".$_GET['sCdAvisoRecebimento'];
            $url .=	"&nCdServico=".str_pad($_GET['nCdServico'], 5, "0", STR_PAD_LEFT);
            $url .=	"&nVlDiametro=0";
            $url .=	"&StrRetorno=xml";
            $resultado = $this->conectar_curl_xml($url);
            $xml_final = $resultado;
            $this->criar_log($cep,$url_full,$xml_final,'online');
            die($xml_final);
        //sempre usar offline
        }elseif($regra==2){
            $xml = new SimpleXMLElement('<Servicos/>');
            if(isset($_GET['nCdServico'])){
                $peso = $this->calcular_peso($_GET['nVlPeso'],$_GET['nVlComprimento'],$_GET['nVlAltura'],$_GET['nVlLargura']);
                $servicos = explode(',',$_GET['nCdServico']);
                foreach($servicos as $servico_id){
                    if($this->servico_valido($servico_id)){
                    $offline = $this->frete_offline($peso,$cep,$servico_id);
                    if($offline['acao']=='ok'){
                        $servico = $xml->addChild('cServico');
                        $servico->addChild('Codigo', str_pad($servico_id, 5, "0", STR_PAD_LEFT));
                        $servico->addChild('Valor', number_format($offline['frete'], 2, ',', ''));
                        $servico->addChild('PrazoEntrega', $offline['prazo']);
                        $servico->addChild('ValorSemAdicionais', number_format($offline['frete'], 2, ',', ''));
                        $servico->addChild('ValorMaoPropria', '0,00');
                        $servico->addChild('ValorAvisoRecebimento', '0,00');
                        $servico->addChild('ValorValorDeclarado', '0,00');
                        $servico->addChild('EntregaDomiciliar', 'S');
                        $servico->addChild('EntregaSabado', 'N');
                        $servico->addChild('Erro', '0');
                        $servico->addChild('MsgErro', '');
                    }else{
                        $servico = $xml->addChild('cServico');
                        $servico->addChild('Codigo', str_pad($servico_id, 5, "0", STR_PAD_LEFT));
                        $servico->addChild('Valor', number_format(0, 2, ',', ''));
                        $servico->addChild('PrazoEntrega', 0);
                        $servico->addChild('ValorSemAdicionais', number_format(0, 2, ',', ''));
                        $servico->addChild('ValorMaoPropria', '0,00');
                        $servico->addChild('ValorAvisoRecebimento', '0,00');
                        $servico->addChild('ValorValorDeclarado', '0,00');
                        $servico->addChild('EntregaDomiciliar', 'S');
                        $servico->addChild('EntregaSabado', 'N');
                        $servico->addChild('Erro', '99');
                        $servico->addChild('MsgErro', 'Frete indisponivel no modo Offline para '.$cep.'');
                    }
                    }
                }
            }
            $xml_final = $xml->asXML();
            $this->criar_log($cep,$url_full,$xml_final,'offline');
            die($xml_final);
        //calcular offline se webservice falhar
        }elseif($regra==3){
            $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
            $url .=	"nCdEmpresa=" . $_GET['nCdEmpresa'];
            $url .=	"&sDsSenha=" . $_GET['sDsSenha'];
            $url .=	"&sCepOrigem=".$_GET['sCepOrigem'];
            $url .=	"&sCepDestino=".$cep;
            $url .=	"&nVlPeso=".$_GET['nVlPeso'];
            $url .=	"&nCdFormato=1";
            $url .=	"&nVlComprimento=".$_GET['nVlComprimento'];
            $url .=	"&nVlLargura=".$_GET['nVlLargura'];
            $url .=	"&nVlAltura=".$_GET['nVlAltura'];
            $url .=	"&sCdMaoPropria=".$_GET['sCdMaoPropria'];
            $url .=	"&nVlValorDeclarado=".$_GET['nVlValorDeclarado'];
            $url .=	"&sCdAvisoRecebimento=".$_GET['sCdAvisoRecebimento'];
            $url .=	"&nCdServico=".str_pad($_GET['nCdServico'], 5, "0", STR_PAD_LEFT);
            $url .=	"&nVlDiametro=0";
            $url .=	"&StrRetorno=xml";
            $resultado = $this->conectar_curl($url);
            if($resultado <> false){
                $xml_final = $resultado;
                $this->criar_log($cep,$url_full,$xml_final,'online');
                die($xml_final);
            }else{
                
            $xml = new SimpleXMLElement('<Servicos/>');
            if(isset($_GET['nCdServico'])){
            $peso = $this->calcular_peso($_GET['nVlPeso'],$_GET['nVlComprimento'],$_GET['nVlAltura'],$_GET['nVlLargura']);
            $servicos = explode(',',$_GET['nCdServico']);
                foreach($servicos as $servico_id){
                    if($this->servico_valido($servico_id)){
                    $offline = $this->frete_offline($peso,$cep,$servico_id);
                    if($offline['acao']=='ok'){
                        $servico = $xml->addChild('cServico');
                        $servico->addChild('Codigo', str_pad($servico_id, 5, "0", STR_PAD_LEFT));
                        $servico->addChild('Valor', number_format($offline['frete'], 2, ',', ''));
                        $servico->addChild('PrazoEntrega', $offline['prazo']);
                        $servico->addChild('ValorSemAdicionais', number_format($offline['frete'], 2, ',', ''));
                        $servico->addChild('ValorMaoPropria', '0,00');
                        $servico->addChild('ValorAvisoRecebimento', '0,00');
                        $servico->addChild('ValorValorDeclarado', '0,00');
                        $servico->addChild('EntregaDomiciliar', 'S');
                        $servico->addChild('EntregaSabado', 'N');
                        $servico->addChild('Erro', '0');
                        $servico->addChild('MsgErro', '');
                    }else{
                        $servico = $xml->addChild('cServico');
                        $servico->addChild('Codigo', str_pad($servico_id, 5, "0", STR_PAD_LEFT));
                        $servico->addChild('Valor', number_format(0, 2, ',', ''));
                        $servico->addChild('PrazoEntrega', 0);
                        $servico->addChild('ValorSemAdicionais', number_format(0, 2, ',', ''));
                        $servico->addChild('ValorMaoPropria', '0,00');
                        $servico->addChild('ValorAvisoRecebimento', '0,00');
                        $servico->addChild('ValorValorDeclarado', '0,00');
                        $servico->addChild('EntregaDomiciliar', 'S');
                        $servico->addChild('EntregaSabado', 'N');
                        $servico->addChild('Erro', '99');
                        $servico->addChild('MsgErro', 'Frete indisponivel no modo Offline para '.$cep.'');
                    }
                    }
                }
            }
            $xml_final = $xml->asXML();
            $this->criar_log($cep,$url_full,$xml_final,'offline');
            die($xml_final);
            }
        }
        }else{
            //calculo invalido
            $xml = new SimpleXMLElement('<Servicos/>');
            $xml_final = $xml->asXML();
            die($xml_final);
        }
    }
    
    public function curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
    
    public function criar_log($para,$url,$xml,$tipo){
        $sql = "INSERT INTO `" . DB_PREFIX . "correios_offline5_logs` (`para`, `tipo`, `url`, `xml`, `data`) VALUES ('".$para."', '".$tipo."', '".$url."', '".$xml."', NOW());";
        $this->db->query($sql);
    }
    
    public function conectar_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->config->get('correios_offline5_timeout'));
        $result = curl_exec($ch);
        curl_close($ch);
        libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($result);
        if(!$xml){
            return false;
        }
        if(!is_object($xml)) {
            return false;
        }
        return $result;
    }
    
    public function conectar_curl_xml($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
    public function calcular_peso($peso,$com,$alt,$lar){
        $peso = str_replace(',', '.', str_replace('.', '', $peso));
        $com = str_replace(',', '.', str_replace('.', '', $com));
        $alt = str_replace(',', '.', str_replace('.', '', $alt));
        $lar = str_replace(',', '.', str_replace('.', '', $lar));
        $peso_cub = number_format(($com*$alt*$lar/6000), 2, '.', '');
        if($peso_cub <= 10){
            return ceil($peso);
        }elseif($peso_cub > 10 && $peso_cub > $peso){
            return ceil($peso_cub);
        }else{
            return ($peso > $peso_cub)?ceil($peso):ceil($peso_cub);
        }
    }
    
    public function servico_valido($id){
        $sql = "SELECT * FROM `" . DB_PREFIX . "correios_offline5_tabela` WHERE servico = '".$id."' AND status = 1";
        $row = $this->db->query($sql);
        return (bool)$row->num_rows;
    }
    
    public function frete_offline($peso,$cep,$servico){
        $cep = substr($cep, 0, 5);
        
        //pega a faixa (custom primeiro)
        $sql = "SELECT * FROM `" . DB_PREFIX . "correios_offline5_base` WHERE (".(int)$cep." BETWEEN `inicio` AND `fim`) ORDER BY esedex ASC";
        $rows = $this->db->query($sql);
        if($rows->row){
            $sql = "SELECT * FROM `" . DB_PREFIX . "correios_offline5_cotacoes` WHERE id_servico = '".$servico."' AND erro = 0 AND peso = '".$peso."' AND `cep_inicio` = '".(int)$rows->row['inicio']."' AND `cep_fim` = '".(int)$rows->row['fim']."'";
            $row = $this->db->query($sql);
            if($row->num_rows >= 1 && $row->row['erro'] == 0){
                return array('acao'=>'ok','frete'=>$row->row['valor'],'prazo'=>$row->row['prazo'],'erro'=>$row->row['erro']);
            }else{
                return array('acao'=>'indisponivel');
            }
        }else{
            return array('acao'=>'indisponivel');
        }
    }
}