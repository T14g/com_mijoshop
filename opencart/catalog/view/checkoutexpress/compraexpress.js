//substitui a funcao nativa
var cart = {
	'remove': function(key) {
		jQuery.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				jQuery('#cart > button').button('loading');
			},
			complete: function() {
				jQuery('#cart > button').button('reset');
			},			
			success: function(json) {
				setTimeout(function () {
					jQuery('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);		
				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/compraexpress' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					jQuery('#cart').load(urlSSL+'index.php?route=common/cart/info');
				}
			}
		});
	}
}
var voucher = {
	'remove': function(key) {
		jQuery.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				jQuery('#cart > button').button('loading');
			},
			complete: function() {
				jQuery('#cart > button').button('reset');
			},
			success: function(json) {
				setTimeout(function () {
					jQuery('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/compraexpress' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					jQuery('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}

jQuery(document).bind('PgwModal::Close', function() {
	jQuery('#pgwModalBackdrop').remove();
	jQuery('#pgwModal').remove();
});

function abrir_url_modal(url,titulo){
    if(jQuery.pgwModal('isOpen')){
        jQuery.pgwModal('close');	
    }
    jQuery.pgwModal({
        url: url,
        title : titulo,
        loadingContent: '<br><center><img src="'+urlSSL+'image/busy.gif"></center><br>',
        closable: true,
        titleBar: true,
        closeOnBackgroundClick: false,
        maxWidth : 700
    });
}

function carregarEstados(estado,ufe){
	jQuery.ajax({
		type: "GET",
		url: urlSSL+"index.php?route=account/registroexpress/estados",
		data: {pais:estado},
		dataType: 'json',
	}).done(function( dados ) {
		var estados = '<option value="">Selecione</option>';
		jQuery.each(dados, function(key,val) {
		estados += '<option value="'+val.zone_id+'">'+val.name+'</option>';
		});
		jQuery('#'+ufe).html(estados);
	});
}

function ativar_mostrar_endereco(tipo){
	jQuery('.lista_enderecos').hide();
	jQuery('#endereco-'+tipo).show();
	jQuery('.botoes_enderecos').removeClass('active');
	jQuery('.'+tipo).addClass('active');
}

function recupersenha(form){
	var dados = jQuery(form).serialize();
	console.log(dados);
	jQuery.ajax({
		type: "POST",
		url: urlSSL+"index.php?route=checkout/compraexpress/atualizarsenhaemail",
		dataType: 'json',
		data: dados,
		beforeSend: function() {
			//bloqueartela();
		},
	}).done(function( dados ) {
			console.log(dados);
			if(dados.erro==true){
			jQuery('#div-rec-msg').html('<div class="alert alert-danger warning" role="alert"><i class="fa fa-exclamation-circle"></i> '+dados.msg+'</div>');
			}else{
			jQuery('#div-rec-msg').html('<div class="alert alert-success success" role="alert"><i class="fa fa-info-circle"></i> '+dados.msg+'</div>');
			setTimeout(function() {
			jQuery.pgwModal('close');
			}, 1000);
			}
	});
	return false;
}

function meiopagamentoescolhido(){
	var meio_pagamento = meio_pagMento_sel();
	if(meio_pagamento){
		return true;
	}else{
		bootbox.dialog({
		message: "Selecione primeiro uma forma de pagamento!",
		title: "Ops",
		});
		return false;	
	}	
}

function logincompraexpressaviso(form){
	var dados = jQuery(form).serialize();
	console.log(dados);
	if(!jQuery(form).valid()){
		return false;
	}
	jQuery.ajax({
		type: "POST",
		url: urlSSL+"index.php?route=checkout/compraexpress/entrar",
		dataType: 'html',
		data: dados,
		beforeSend: function() {
			bloqueartela();
		},
	}).done(function( html ) {
			if(html==0){
				jQuery('#div-login-msg-aviso-modal').html('<div class="alert alert-danger warning" role="alert"><i class="fa fa-exclamation-circle"></i> Ops, e-mail ou senha invalida!</div>');
				jQuery.unblockUI();
				jQuery('.blockUI').remove();
			}else{
				jQuery.pgwModal('close');
				estalogadoounao();
			}
	});
	return false;
}

function carregartelaclientelogado(clienteid){
	jQuery.ajax({
		type: "POST",
		url: urlSSL+"index.php?route=checkout/compraexpress/estalogado",
		dataType: 'html',
		data: {id:clienteid},
		beforeSend: function() {
			//bloqueartela();
		},
	}).done(function( html ) {
			jQuery('#html-compraexpress').html(html);
			jQuery.unblockUI();
			jQuery('.blockUI').remove();
	});
	return false;
}

function aplicarmeiopagamento(meio){
	//roberto
	if(jQuery("#aceite").is(':checked') == false){
 		bootbox.dialog({
   			message: "Antes de selecionar a forma de pagamento é preciso aceitar os termos de do pedido!",
   			title: "Aceite os termos do pedido",
 		});
 		jQuery("input[name='meios_pagamento']").prop('checked', false);
 		return false;
	}
	var produto_digital = jQuery('input[name="produto_digital"]').val();
	var meio_entrega = meio_entrega_sel();
	//verifica se o produto tem um meio de entrega selecionado ou e digital
	if(produto_digital=='false' && meio_entrega==false){
		bootbox.dialog({
		message: "Selecione primeiro o meio de entrega!",
		title: "Atenção",
		});
		meio_pagMento_sel_reset();
		return false;
	}
	if(meio==''){
		return false;
	}
	console.log('aplicou funcao meio pagamento '+meio+'!');
	var comentario = jQuery('#comentario_pedido').val();
    var comentario = jQuery('#comentario_pedido').val();
    if (typeof comentario == 'undefined') {
        var comentario = '';
    }
	jQuery.ajax({
		type: "POST",
		url: urlSSL+"index.php?route=checkout/payment_method/save",
		data: {payment_method:meio,agree:1,comment:comentario},
		dataType: 'html',
	}).done(function( html ) {
		jQuery('#meio_selecionado_express').val(meio);
		carregar_detalhes_pagar(meio);
	});
}

function carregar_detalhes_pagar(meio){
	if(meio==''){
		return false;
	}
	jQuery(":radio[value="+meio+"]").prop('checked',true);
	var idcliente = jQuery('#cliente_id').val();
	var clientehash = jQuery('#cliente_hash').val();
	jQuery.ajax({
		type: "GET",
		url: urlSSL+"index.php?route=checkout/confirm",
		data: {id:idcliente,hash:clientehash},
		dataType: 'html',
		beforeSend: function() {
			bloqueartela();
		},
	}).done(function( html ) {
        var div = jQuery('#html-detalhes-como-pagar');
		div.html(html);
		jQuery.unblockUI();
		jQuery('.blockUI').remove();
        console.log('scrolll div');
        //jQuery.scrollTo('#gatilho-pagamento', 800, {});
        jQuery('html, body').animate({
        scrollTop: jQuery("#html-detalhes-como-pagar").offset().top
        }, 2000);
		carregarcarrinho();
	});
}

function carregarcarrinho(){
	jQuery.ajax({
	type: "GET",
	url: urlSSL+"index.php?route=common/cart/info",
	dataType: 'HTML',
	}).done(function( html ) {
	console.log('carrinho carregado!');
	jQuery('#cart,#cart_block').html(html);
	});	
}

function aplicarmeioentrega(meio){
	if(meio==''){
		return false;
	}
	console.log('aplicando funcao meio entrega '+meio+'!');
	jQuery.ajax({
        type: "POST",
        url: urlSSL+"index.php?route=checkout/compraexpress/shipping",
        data: {shipping_method:meio},
        dataType: 'JSON',
	}).done(function( html ) {
        console.log('aplicou funcao meio entrega '+meio+'!');
        carregarcarrinho();
        var meio_pagamento = meio_pagMento_sel();
        if(meio_pagamento){
            carregar_detalhes_pagar(meio_pagamento);
        }else{
            jQuery.ajax({
                type: "GET",
                url: urlSSL+"index.php?route=checkout/compraexpress/ver_itens_html",
                dataType: 'html',
            }).done(function( html ) {
                var div = jQuery('#tabela-produtos');
                div.html(html);
            });
        }
	});
}

function dadosentregalogadojson(endereco){
	jQuery.ajax({
		type: "GET",
		url: urlSSL+"index.php?route=checkout/compraexpress/jsonendereco&id="+endereco,
		dataType: 'json',
	}).done(function( dados ) {
		console.log(dados);
		jQuery('#idendereco_e').val(dados.id);
		jQuery('#nome_completo_e').val(dados.nome+' '+dados.sobrenome);
		jQuery('#cep_e').val(dados.cep);
		jQuery('#logradouro_e').val(dados.logradouro);
		jQuery('#numero_e').val(dados.numero);
		jQuery('#bairro_e').val(dados.bairro);
		jQuery('#cidade_e').val(dados.cidade);
		jQuery('#uf_e').val(dados.uf);
		jQuery('#complemento_e').val(dados.complemento);
	});
}

function meio_pagMento_sel(){
	var selectedValue = false;
	var radios = document.getElementsByName("meios_pagamento");
	for(var i = 0; i < radios.length; i++) {
    if(radios[i].checked) selectedValue = radios[i].value;   
	}
	return selectedValue;
}

function meio_pagMento_sel_reset(){
	var radios = document.getElementsByName("meios_pagamento");
	for(var i = 0; i < radios.length; i++) {
    radios[i].checked = false;  
	}
	return true;
}

function meio_entrega_sel(){
	var selectedValue = false;
	var radios = document.getElementsByName("meios_entrega");
	for(var i = 0; i < radios.length; i++) {
    if(radios[i].checked) selectedValue = radios[i].value;   
	}
	return selectedValue;
}

function tipo_cliente_cliente(tipo){
	var jQueryradios = jQuery('input:radio[name=tipo_pessoa]');
	if(jQueryradios.is(':checked') === false) {
		jQueryradios.filter('[value='+tipo+']').prop('checked', true);
	}
	if(tipo=='pj'){
			jQuery('#registro-nome').html('Empresa');
			jQuery('input[name="nome"]').attr("placeholder","Empresa");
			jQuery('#registro-fiscal').html('CNPJ');
			jQuery('#registro-rgie').html('IE');
			jQuery('input[name="fiscal"]').attr("placeholder","CNPJ").attr('maxlength', '17'); 
	}else{
			jQuery('#registro-nome').html('Nome');
			jQuery('input[name="nome"]').attr("placeholder","Nome");
			jQuery('#registro-fiscal').html('CPF');
			jQuery('input[name="fiscal"]').attr("placeholder","CPF").attr('maxlength', '14'); 
			jQuery('#registro-rgie').html('RG');
	}
}

function limparemail(){
	console.log('limpar email!');
	bootbox.confirm("Confirma sair?", function(result) {
	if(result==true){
	jQuery.ajax({
		type: "POST",
		url: urlSSL+"index.php?route=checkout/compraexpress/limparemail",
		dataType: 'html',
	}).done(function( html ) {
		location.href = urlSSL+"index.php?route=account/logout";
	});	
	}
	}); 
	return true;
}

function estalogadoounao(){
	jQuery.ajax({
        url: urlSSL+'index.php?route=checkout/compraexpress/estalogado',
        dataType: 'html',
        success: function(html) {
		   jQuery('#html-compraexpress').html(html);
		   jQuery.unblockUI();
		   jQuery('.blockUI').remove();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

function bloqueartela(){
	jQuery.blockUI({ message: '<img src="'+urlSSL+'image/busy.gif" />',css: { border: '0px solid #000', 'background-color': 'transparent', padding: '10px'}});
}

function bloquearteladiv(div){
	jQuery('#'+div).block({message: '<img src="'+urlSSL+'image/busy.gif" />'}); 
}

function validaCPFCheckoutExpress(s) {
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

function validaCNPJCheckoutExpress(CNPJ) {
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

function soNums(e) {
	if (document.all){var evt=event.keyCode;}
	else{var evt = e.charCode;}
	if (evt <20 || (evt >47 && evt<58)){return true;}
	return false;
}

function validarCpfCnpjCheckoutExpress(valor) {
	var s = (valor).replace(/\D/g,'');
	var tam=(s).length;
	if (!(tam==11 || tam==14)){
		return false;
	}
	if (tam==11 ){
		if (!validaCPFCheckoutExpress(s)){
			return false;
		}
		return true;
	}		
	if (tam==14){
		if(!validaCNPJCheckoutExpress(s)){
			return false;			
		}
		return true;
	}
}

function unserialize(data) {
  //  discuss at: http://phpjs.org/functions/unserialize/
  // original by: Arpad Ray (mailto:arpad@php.net)
  // improved by: Pedro Tainha (http://www.pedrotainha.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Chris
  // improved by: James
  // improved by: Le Torbi
  // improved by: Eli Skeggs
  // bugfixed by: dptr1988
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //  revised by: d3x
  //    input by: Brett Zamir (http://brett-zamir.me)
  //    input by: Martin (http://www.erlenwiese.de/)
  //    input by: kilops
  //    input by: Jaroslaw Czarniak
  //        note: We feel the main purpose of this function should be to ease the transport of data between php & js
  //        note: Aiming for PHP-compatibility, we have to translate objects to arrays
  //   example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
  //   returns 1: ['Kevin', 'van', 'Zonneveld']
  //   example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
  //   returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}

  var that = this,
    utf8Overhead = function (chr) {
      // http://phpjs.org/functions/unserialize:571#comment_95906
      var code = chr.charCodeAt(0);
      if (code < 0x0080) {
        return 0;
      }
      if (code < 0x0800) {
        return 1;
      }
      return 2;
    };
  error = function (type, msg, filename, line) {
    throw new that.window[type](msg, filename, line);
  };
  read_until = function (data, offset, stopchr) {
    var i = 2,
      buf = [],
      chr = data.slice(offset, offset + 1);

    while (chr != stopchr) {
      if ((i + offset) > data.length) {
        error('Error', 'Invalid');
      }
      buf.push(chr);
      chr = data.slice(offset + (i - 1), offset + i);
      i += 1;
    }
    return [buf.length, buf.join('')];
  };
  read_chrs = function (data, offset, length) {
    var i, chr, buf;

    buf = [];
    for (i = 0; i < length; i++) {
      chr = data.slice(offset + (i - 1), offset + i);
      buf.push(chr);
      length -= utf8Overhead(chr);
    }
    return [buf.length, buf.join('')];
  };
  _unserialize = function (data, offset) {
    var dtype, dataoffset, keyandchrs, keys, contig,
      length, array, readdata, readData, ccount,
      stringlength, i, key, kprops, kchrs, vprops,
      vchrs, value, chrs = 0,
      typeconvert = function (x) {
        return x;
      };

    if (!offset) {
      offset = 0;
    }
    dtype = (data.slice(offset, offset + 1))
      .toLowerCase();

    dataoffset = offset + 2;

    switch (dtype) {
    case 'i':
      typeconvert = function (x) {
        return parseInt(x, 10);
      };
      readData = read_until(data, dataoffset, ';');
      chrs = readData[0];
      readdata = readData[1];
      dataoffset += chrs + 1;
      break;
    case 'b':
      typeconvert = function (x) {
        return parseInt(x, 10) !== 0;
      };
      readData = read_until(data, dataoffset, ';');
      chrs = readData[0];
      readdata = readData[1];
      dataoffset += chrs + 1;
      break;
    case 'd':
      typeconvert = function (x) {
        return parseFloat(x);
      };
      readData = read_until(data, dataoffset, ';');
      chrs = readData[0];
      readdata = readData[1];
      dataoffset += chrs + 1;
      break;
    case 'n':
      readdata = null;
      break;
    case 's':
      ccount = read_until(data, dataoffset, ':');
      chrs = ccount[0];
      stringlength = ccount[1];
      dataoffset += chrs + 2;

      readData = read_chrs(data, dataoffset + 1, parseInt(stringlength, 10));
      chrs = readData[0];
      readdata = readData[1];
      dataoffset += chrs + 2;
      if (chrs != parseInt(stringlength, 10) && chrs != readdata.length) {
        error('SyntaxError', 'String length mismatch');
      }
      break;
    case 'a':
      readdata = {};

      keyandchrs = read_until(data, dataoffset, ':');
      chrs = keyandchrs[0];
      keys = keyandchrs[1];
      dataoffset += chrs + 2;

      length = parseInt(keys, 10);
      contig = true;

      for (i = 0; i < length; i++) {
        kprops = _unserialize(data, dataoffset);
        kchrs = kprops[1];
        key = kprops[2];
        dataoffset += kchrs;

        vprops = _unserialize(data, dataoffset);
        vchrs = vprops[1];
        value = vprops[2];
        dataoffset += vchrs;

        if (key !== i)
          contig = false;

        readdata[key] = value;
      }

      if (contig) {
        array = new Array(length);
        for (i = 0; i < length; i++)
          array[i] = readdata[i];
        readdata = array;
      }

      dataoffset += 1;
      break;
    default:
      error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
      break;
    }
    return [dtype, dataoffset - offset, typeconvert(readdata)];
  };

  return _unserialize((data + ''), 0)[2];
}

//aplica padroes
jQuery.validator.setDefaults({
  errorClass: "campo_invalido",
  ignore: ".ignore",
  errorElement: "span",
});

//meios custom validator
jQuery(".validar_form").validate();
jQuery.validator.addMethod("fiscalValidator", function(value, element) {
	return this.optional(element) || validarCpfCnpjCheckoutExpress(value);
}, "Informe seu CPF/CNPJ correto");

jQuery.validator.addMethod("nomeValidator", function(value, element) {
	var count = (value.match(/[a-zA-Z0-9] [a-zA-Z0-9]/g) || []).length;
	console.log(count);
	return this.optional(element) || count;
}, "Informe seu nome/empresa correto");

jQuery(function(jQuery){
   //jQuery(".mask_data").mask("99/99/9999");
   //jQuery(".mask_telefone").mask("(99)999999999");
   //jQuery(".mask_cep").mask("99999-999");
   //jQuery(".mask_fiscal").mask("99999999999999");
});