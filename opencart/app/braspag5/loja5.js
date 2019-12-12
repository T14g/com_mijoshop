function check_elo(numero){
	var bin = numero.substring(0, 6);
	var cc = Number(bin);
	if (cc==431274){
		return true;
	}else if(cc>=650035 && cc<=650051){
		return true;
	}else if(cc==438935){
		return true;
	}else if(cc>=650405 && cc<=650439){
		return true;
	}else if(cc==451416){
		return true;
	}else if(cc>=650485 && cc<=650538){
		return true;
	}else if(cc==457393){
		return true;
	}else if(cc>=650541 && cc<=650598){
		return true;
	}else if(cc==457631){
		return true;
	}else if(cc>=650700 && cc<= 650718){
		return true;
	}else if(cc==457632){
		return true;
	}else if(cc>=650720 && cc<= 650727){
		return true;
	}else if(cc==504175){
		return true;
	}else if(cc>=650901 && cc<=650920){
		return true;
	}else if(cc>=506699 && cc<=506778){
		return true;
	}else if(cc>=651652 && cc<=651679){
		return true;
	}else if(cc>=509000 && cc<=509999){
		return true;
	}else if(cc>=655000 && cc<=655019){
		return true;
	}else if(cc==627780){
		return true;
	}else if(cc>=655021 && cc<=655058){
		return true;
	}else if(cc==636297){
		return true;
	}else{
		return false;
	}
}

function detectar_bandeira_cartao_credito(numero){
	var result = '';
	var bin = (numero).replace(/\D/g,'');
	if(check_elo(bin)){
	result = "elo";		
	}else{
	if (/^3[47][0-9]{13}$/.test(bin)) {//se amex
    result = "amex";
	} else if (/^4[0-9]{12}(?:[0-9]{3})?$/.test(bin)) {//se visa
    result = "visa";
	} else if (/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/.test(bin)) {//se diners
    result = "diners";
	} else if (/^((636368)|(438935)|(506726)|(457631)|(401178)|(506727)|(506742)|(506741)|(506778)|(457632)|(506744)|(636297)|(627780)|(506731)|(506699))/.test(bin)) {//se elo
    result = "elo";		
	} else if(/^6(?:011|5[0-9]{2})[0-9]{12}$/.test(bin)) {//se discover
	result = "discover";	
	} else if(/^(?:2131|1800|35\d{3})\d{11}$/.test(bin)) {//se jcb
	result = "jcb";	
	} else if(/^(50)\d+$/.test(bin)) {//se aura
	result = "aura";	
	} else if(/^5[1-5][0-9]{14}$/.test(bin)) {//se mastercard
	result = "mastercard";	
	} else if(/^(38[0-9]{17}|60[0-9]{14})$/.test(bin)) {//se hipercard
	result = "hipercard";	
	}
	}
	console.log(result);
	if(result!=''){
	//auto seleciona a bandeira
	jQuery('#bandeira').val(result);
	jQuery('.cartoes').css('opacity', '0.3');	
	jQuery('.'+result).css('opacity', '1');
	//aplica a bandeira
	$('#html-logo').html('<img id="html-logo-ok" src="app/braspag5/icones-transparente/'+result+'.png">');
	//carrega as parcelas
	carregar_parcelas(result);
	}
	return result;
}

function cartao_manual_s(result){
	jQuery('#bandeira').val(result);
	jQuery('.cartoes,.cartoesdebito').css('opacity', '0.3');	
	jQuery('.'+result).css('opacity', '1');
	carregar_parcelas(result);
}

function detectar_bandeira_cartao_debito(numero){
	var result = '';
	var bin = (numero).replace(/\D/g,'');
	if (/^4[0-9]{12}(?:[0-9]{3})?$/.test(bin)) {//se visa
    result = "electron";
	} else if(/^5[1-5][0-9]{14}$/.test(bin)) {//se mastercard
	result = "maestro";	
	}
	console.log(result);
	if(result!=''){
	jQuery('#bandeira').val(result);
	jQuery('.cartoesdebito').css('opacity', '0.3');	
	jQuery('.'+result).css('opacity', '1');
	carregar_parcelas_debito(result);
	}
	return result;
}

function carregar_parcelas(meio) {
	var pedido = $('input[name="pedido_cielo"]').val();
	var hash = $('input[name="hash_cielo"]').val();
	$('#parcelas').html('<option value="">Carregando...</option>');
	$.getJSON('index.php?route=payment/braspag5/parcelas&id='+meio+'&pedido='+pedido+'&hash='+hash+'', function(data) {
	var items = '';
	$.each(data, function(key, val) {
	items += '<option value="' + key + '">' + val + '</option>';
	});
	$('#parcelas').html(items);
	});
}

function carregar_parcelas_debito(meio) {
	var pedido = $('input[name="pedido_cielo"]').val();
	var hash = $('input[name="hash_cielo"]').val();
	$('#parcelas').html('<option value="">Carregando...</option>');
	$.getJSON('index.php?route=payment/braspag5debito/parcelas&id='+meio+'&pedido='+pedido+'&hash='+hash+'', function(data) {
	var items = '';
	$.each(data, function(key, val) {
	items += '<option value="' + key + '">' + val + '</option>';
	});
	$('#parcelas').html(items);
	});
}

function jcv_luhnCheckbraspag5(valor) {
		var cardNumber = (valor).replace(/\D/g,'');
        if (jcv_isLuhnNumbraspag5(cardNumber)) {
            var no_digit = cardNumber.length;
            var oddoeven = no_digit & 1;
            var sum = 0;
            for (var count = 0; count < no_digit; count++) {
                var digit = parseInt(cardNumber.charAt(count));
                if (!((count & 1) ^ oddoeven)) {
                    digit *= 2;
                    if (digit > 9) digit -= 9;
                };
                sum += digit;
            };
            if (sum == 0) return false;
            if (sum % 10 == 0) return true;
        };
        return false;
}

function jcv_isLuhnNumbraspag5(argvalue) {
        argvalue = argvalue.toString();
        if (argvalue.length == 0) {
            return false;
        }
        for (var n = 0; n < argvalue.length; n++) {
            if ((argvalue.substring(n, n+1) < "0") ||
                (argvalue.substring(n,n+1) > "9")) {
                return false;
            }
        }
        return true;
}

function upbraspag5(lstr){
	var str=lstr.value;
	lstr.value=str.toUpperCase();
}

function downbraspag5(lstr){
	var str=lstr.value;
	lstr.value=str.toLowerCase();
}

function isNumberKeybraspag5(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;

	return true;
}

function validaCPFbraspag5(s) {
	var c = s.substr(0,9);
	var dv = s.substr(9,2);
	var d1 = 0;
	for (var i=0; i<9; i++) {
		d1 += c.charAt(i)*(10-i);
 	}
	if (d1 == 0) return false;
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(0) != d1){
		return false;
	}
	d1 *= 2;
	for (var i = 0; i < 9; i++)	{
 		d1 += c.charAt(i)*(11-i);
	}
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(1) != d1){
		return false;
	}
    return true;
}

function validaCNPJbraspag5(CNPJ) {
	var a = new Array();
	var b = new Number;
	var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
	for (i=0; i<12; i++){
		a[i] = CNPJ.charAt(i);
		b += a[i] * c[i+1];
	}
	if ((x = b % 11) < 2) { a[12] = 0 } else { a[12] = 11-x }
	b = 0;
	for (y=0; y<13; y++) {
		b += (a[y] * c[y]);
	}
	if ((x = b % 11) < 2) { a[13] = 0; } else { a[13] = 11-x; }
	if ((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){
		return false;
	}
	return true;
}

function validarCpfCnpjbraspag5(valor) {
	var s = (valor).replace(/\D/g,'');
	var tam=(s).length;
	if (!(tam==11 || tam==14)){
		return false;
	}
	if (tam==11 ){
		if (!validaCPFbraspag5(s)){
			return false;
		}
		return true;
	}		
	if (tam==14){
		if(!validaCNPJbraspag5(s)){
			return false;			
		}
		return true;
	}
}

//meios custom validator
jQuery.validator.addMethod("validacpfcnpj", function(value, element) {
  return this.optional(element) || validarCpfCnpjbraspag5(value);
}, "Digite CPF do titular valido");

jQuery.validator.addMethod("validacartao", function(value, element) {
  return this.optional(element) || jcv_luhnCheckbraspag5(value);
}, "Digite um cart\u00e3o valido");

var currentTime = new Date();

//validar credito
$(".form_pagamento_cartao").validate({
submitHandler: function(form) {
	$.blockUI({ message: '<img src="app/braspag5/busy.gif" />',css: { border: '0px solid #000', 'background-color': 'transparent', padding: '10px'}}); 
	return true;
},
ignore: ".ignore_campo",
errorClass: "ops_campo_erro",
rules: {
titular: {
required: true,
minlength: 5
},
fiscal: {
required: true,
validacpfcnpj:true
},
numero: {
required: true,
validacartao:true
},
validadem: {
required: true,
min: 1,
max: 12
},
validadea: {
required: true,
min: currentTime.getFullYear(),
max: currentTime.getFullYear()+30
},
codigo: {
required: true,
minlength: 3,
maxlength: 4
},
parcelas: "required"
},
messages: {
titular: "Informe nome do titular",
fiscal: "Informe o CPF/CNPJ do titular",
numero: "Informe o n\u00famero do cart\u00e3o valido",
validadem: "Informe o mes!",
validadea: "Informe o ano!",
codigo: "Informe o CVV!",
parcelas: "Qual a parcela?",
}
});