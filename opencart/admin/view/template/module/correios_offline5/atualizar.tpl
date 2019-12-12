<?php 
echo $header; 
?>

<script>
//faixas ceps base
var faixa_ceps = [];
<?php foreach($ceps_base AS $cep){ ?>
faixa_ceps.push('<?php echo $cep['base_cep'];?>');
<?php } ?>

//faixas ceps de
var faixa_ceps_inicio = [];
<?php foreach($ceps_base AS $cep){ ?>
faixa_ceps_inicio.push('<?php echo $cep['inicio'];?>');
<?php } ?>

//faixas ceps para
var faixa_ceps_fim = [];
<?php foreach($ceps_base AS $cep){ ?>
faixa_ceps_fim.push('<?php echo $cep['fim'];?>');
<?php } ?>

//calcula frete 
var progresso = 0;
function calcular_fretes(inicio){
    $('#botao-atualizar-tabela').attr("disabled","disabled").html('(aguarde o termino)');
    var incremento = inicio;
    var arrayStop = faixa_ceps.length-1;
    var pulo = <?php echo number_format($pulo, 2, '.', '');?>;
    //$.each(faixa_ceps, function (index, value) {
        $.ajax({
            method: "POST",
            url: "index.php?route=module/correios_offline5/api&token=<?php echo $token;?>",
            data: { cep: faixa_ceps[inicio], index: inicio, servico: <?php echo $detalhes->row['cod'];?>, de: faixa_ceps_inicio[inicio], para: faixa_ceps_fim[inicio] }
        }).done(function( ret ) {
            
            //incrementa o bar
            console.log(progresso);
            progresso += pulo;
            
            //regra para nao passar de 100
            if(progresso >= 100){
                progresso = 100;
            }
            
            //alimenta o bar
            $('.progress-bar').css('width', progresso.toFixed(2)+'%'); 
            $('.progress-bar').attr('aria-valuenow', progresso.toFixed(2)); 
            $('.progress-bar').html(progresso.toFixed(2)+'%'); 
            
            //verifica se e a ultima
            incremento += 1;
            if(inicio < arrayStop){
                calcular_fretes(incremento);
            }else{
                console.log('fim');
                $('.progress-bar').css('width', '100%'); 
                $('.progress-bar').attr('aria-valuenow', 100); 
                $('.progress-bar').html('100%'); 
                $('#botao-atualizar-tabela').attr("disabled","disabled").html('Concluido');
                setTimeout(function(){history.back();}, 5000);
            }
            
        });
    //});
}
</script>

<?php echo $column_left; ?>

<div id="content">
<div class="page-header">
<div class="container-fluid">

<div class="pull-right">
<a href="<?php echo $cancel; ?>" class="btn btn-info">Voltar <i class="fa fa-reply"></i></a></div>

<h1>Atualizar Tabela</h1>

<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
</ul>
</div>
</div>
<div class="container-fluid">

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-list"></i> Atualizar Tabela</h3>
</div>
<div class="panel-body">
<?php //print_r($ceps_base);?>
<div class="alert alert-info" role="alert">Ao clicar em atualizar tabela o sistema ira automaticamente calcular um frete base para todos os CEPs Bases cadastrados em sua loja para o servi&ccedil;o de entrega Correios qual selecionou, o mesmo poder&aacute; levar alguns minutos e a janela n&atilde;o pode ser fechada at&eacute; concluir.</div>

<form class="form-horizontal">
<div class="form-group">
<label for="inputEmail3" class="col-sm-2 control-label">Servi&ccedil;o Correios</label>
<div class="col-sm-10">
<p class="form-control-static"><?php echo $detalhes->row['cod'];?> - <?php echo $detalhes->row['nome'];?></p>
</div>
</div>
<div class="form-group">
<label for="inputEmail3" class="col-sm-2 control-label"></label>
<div class="col-sm-10">
<br>
<div class="progress">
<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
</div>
</div>
</div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    <?php if($detalhes->row['com_contrato']==0){ ?>
      <button onclick="calcular_fretes(0)" type="button" id="botao-atualizar-tabela" class="btn btn-info">Atualizar Tabela</button>
    <?php }else{ ?>
    <?php if(empty($login) || empty($senha)){ ?>
        <button type="button" class="btn btn-danger">Informar dados de contrato correios!</button>
    <?php }else{  ?>
        <button onclick="calcular_fretes(0)" type="button" id="botao-atualizar-tabela" class="btn btn-info">Atualizar Tabela</button>
    <?php } ?>
    <?php } ?>
    </div>
  </div>
</form>

</div>
</div>

</div>
</div>


<?php echo $footer; ?>