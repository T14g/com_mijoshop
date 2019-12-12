<div class="row form_modal">
<div class="col-md-12 xl-100">

<!-- modal aplicar vale presente ou cupom -->
<div id="erros-cupom"></div>
<div class="panel-group" id="accordion">

<div class="panel panel-default">
<div class="panel-heading">
<h4 class="panel-title"><a href="#collapse-coupon" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">Cupom de desconto <i class="fa fa-caret-down"></i></a></h4>
</div>
<div id="collapse-coupon" class="panel-collapse collapse">
<div class="panel-body">
<div class="input-group" style="width:100%">
<input type="text" style="width:80%" name="coupon" value="<?php echo $cupom;?>" placeholder="Digite ou cole aqui!" id="input-coupon" class="form-control">
<input style="border-radius: 0px 5px 5px 0px;" type="button" value="Aplicar" id="button-coupon" data-loading-text="Aguarde..." class="btn btn-primary button">
</div>
<script type="text/javascript"><!--
$('#button-coupon').on('click', function() {
$.ajax({
<?php if(version_compare(VERSION, '2.3.0.0', '>=')){ ?>
url: 'index.php?route=extension/total/coupon/coupon',
<?php }elseif(version_compare(VERSION, '2.1.0.0', '>=')){ ?>
url: 'index.php?route=total/coupon/coupon',
<?php }else{ ?>
url: 'index.php?route=checkout/coupon/coupon',
<?php } ?>
type: 'post',
data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
dataType: 'json',
beforeSend: function() {
    $('#button-coupon').button('loading');
},
complete: function() {
    $('#button-coupon').button('reset');
},
success: function(json) {
if (json['error']) {
    $('#erros-cupom').html('<div style="width: auto;" class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
}else{
    var meio_pagamento = meio_pagMento_sel();
    if(meio_pagamento){
    carregar_detalhes_pagar(meio_pagamento);
    }
    $('#erros-cupom').html('<div style="width: auto;" class="alert alert-success success"><i class="fa fa-exclamation-circle"></i> Cupom aplicado com sucesso!<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
    setTimeout(function() {
    $.pgwModal('close');
    }, 1000);
}
}
});
});
//--></script>
</div>
</div>
</div>
<div style="display: none" class="panel panel-default">
<div class="panel-heading">
<h4 class="panel-title"><a href="#collapse-voucher" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Vale presente <i class="fa fa-caret-down"></i></a></h4>
</div>
<div id="collapse-voucher" class="panel-collapse collapse">
<div class="panel-body">
<div class="input-group" style="width:100%">
<input type="text" style="width:80%" name="voucher" value="<?php echo $vale;?>" placeholder="Digite ou cole aqui!" id="input-voucher" class="form-control">
<input style="border-radius: 0px 5px 5px 0px;" type="submit" value="Aplicar" id="button-voucher" data-loading-text="Aguarde..." class="btn btn-primary button">
</div>
<script type="text/javascript"><!--
$('#button-voucher').on('click', function() {
$.ajax({
<?php if(version_compare(VERSION, '2.3.0.0', '>=')){ ?>
url: 'index.php?route=extension/total/voucher/voucher',
<?php }elseif(version_compare(VERSION, '2.1.0.0', '>=')){ ?>
url: 'index.php?route=total/voucher/voucher',
<?php }else{ ?>
url: 'index.php?route=checkout/voucher/voucher',
<?php } ?>
type: 'post',
data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
dataType: 'json',
beforeSend: function() {
    $('#button-voucher').button('loading');
},
complete: function() {
    $('#button-voucher').button('reset');
},
success: function(json) {
if (json['error']) {
    $('#erros-cupom').html('<div style="width: auto;" class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
}else{
    var meio_pagamento = meio_pagMento_sel();
    if(meio_pagamento){
        carregar_detalhes_pagar(meio_pagamento);
    }
    $('#erros-cupom').html('<div style="width: auto;" class="alert alert-success success"><i class="fa fa-exclamation-circle"></i> Vale presente aplicado com sucesso!<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
    setTimeout(function() {
    $.pgwModal('close');
    }, 1000);
}
}
});
});
//--></script>
</div>
</div>
</div>
</div>

</div>
</div>