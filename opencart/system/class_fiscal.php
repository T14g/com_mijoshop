<?php
class ValidaCPFCNPJ {
	function __construct ( $valor = null ) {
		$this->valor = preg_replace( '/[^0-9]/', '', $valor );
		$this->valor = (string)$this->valor;
	}
	protected function verifica_cpf_cnpj () {
		// Verifica CPF
		if ( strlen( $this->valor ) === 11 ) {
			return 'CPF';
		} 
		// Verifica CNPJ
		elseif ( strlen( $this->valor ) === 14 ) {
			return 'CNPJ';
		} 
		// Não retorna nada
		else {
			return false;
		}
	}
	protected function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
		// Faz a soma dos dígitos com a posição
		// Ex. para 10 posições:
		//   0    2    5    4    6    2    8    8   4
		// x10   x9   x8   x7   x6   x5   x4   x3  x2
		//   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
		for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
			// Preenche a soma com o dígito vezes a posição
			$soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );

			// Subtrai 1 da posição
			$posicoes--;

			// Parte específica para CNPJ
			// Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
			if ( $posicoes < 2 ) {
				// Retorno a posição para 9
				$posicoes = 9;
			}
		}

		// Captura o resto da divisão entre $soma_digitos dividido por 11
		// Ex.: 196 % 11 = 9
		$soma_digitos = $soma_digitos % 11;

		// Verifica se $soma_digitos é menor que 2
		if ( $soma_digitos < 2 ) {
			// $soma_digitos agora será zero
			$soma_digitos = 0;
		} else {
			// Se for maior que 2, o resultado é 11 menos $soma_digitos
			// Ex.: 11 - 9 = 2
			// Nosso dígito procurado é 2
			$soma_digitos = 11 - $soma_digitos;
		}

		// Concatena mais um dígito aos primeiro nove dígitos
		// Ex.: 025462884 + 2 = 0254628842
		$cpf = $digitos . $soma_digitos;

		// Retorna
		return $cpf;
	}

	protected function valida_cpf() {
		// Captura os 9 primeiros dígitos do CPF
		// Ex.: 02546288423 = 025462884
		$digitos = substr($this->valor, 0, 9);

		// Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
		$novo_cpf = $this->calc_digitos_posicoes( $digitos );

		// Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
		$novo_cpf = $this->calc_digitos_posicoes( $novo_cpf, 11 );

		// Verifica se o novo CPF gerado é idêntico ao CPF enviado
		if ( $novo_cpf === $this->valor ) {
			// CPF válido
			return true;
		} else {
			// CPF inválido
			return false;
		}
	}

	protected function valida_cnpj () {
		// O valor original
		$cnpj_original = $this->valor;

		// Captura os primeiros 12 números do CNPJ
		$primeiros_numeros_cnpj = substr( $this->valor, 0, 12 );

		// Faz o primeiro cálculo
		$primeiro_calculo = $this->calc_digitos_posicoes( $primeiros_numeros_cnpj, 5 );

		// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
		$segundo_calculo = $this->calc_digitos_posicoes( $primeiro_calculo, 6 );

		// Concatena o segundo dígito ao CNPJ
		$cnpj = $segundo_calculo;

		// Verifica se o CNPJ gerado é idêntico ao enviado
		if ( $cnpj === $cnpj_original ) {
			return true;
		}
	}

	public function valida () {
		// Valida CPF
		if ( $this->verifica_cpf_cnpj() === 'CPF' ) {
			// Retorna true para cpf válido
			return $this->valida_cpf();
		} 
		elseif ( $this->verifica_cpf_cnpj() === 'CNPJ' ) {
			return $this->valida_cnpj();
		} 
		else {
			return false;
		}
	}
	
	public function valida_tipo () {
		if ( $this->verifica_cpf_cnpj() === 'CPF' ) {
			return array('valido'=>$this->valida_cpf(),'tipo'=>'pf');
		} 
		elseif ( $this->verifica_cpf_cnpj() === 'CNPJ' ) {
			return array('valido'=>$this->valida_cnpj(),'tipo'=>'pj');
		} 
		else {
			return array('valido'=>false,'tipo'=>'pf');
		}
	}

	public function formata() {
		$formatado = false;
		if ( $this->verifica_cpf_cnpj() === 'CPF' ) {
			if ( $this->valida_cpf() ) {
				$formatado  = substr( $this->valor, 0, 3 ) . '.';
				$formatado .= substr( $this->valor, 3, 3 ) . '.';
				$formatado .= substr( $this->valor, 6, 3 ) . '-';
				$formatado .= substr( $this->valor, 9, 2 ) . '';
			}
		} 
		elseif ( $this->verifica_cpf_cnpj() === 'CNPJ' ) {
			if ( $this->valida_cnpj() ) {
				$formatado  = substr( $this->valor,  0,  2 ) . '.';
				$formatado .= substr( $this->valor,  2,  3 ) . '.';
				$formatado .= substr( $this->valor,  5,  3 ) . '/';
				$formatado .= substr( $this->valor,  8,  4 ) . '-';
				$formatado .= substr( $this->valor, 12, 14 ) . '';
			}
		} 
		return $formatado;
	}
}
?>