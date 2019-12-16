<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
             <div class="form-group">
                <label class="control-label" for="input-name">Nome do curso</label>
                <input type="text" name="filter_name" value="<?php echo $nome_curso; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              

              <div class="form-group">
                <label class="control-label" for="input-date-start">De:</label>
                <div class="input-group date">
                  <input type="text" name="data_start_curso" value="<?php echo $data_inicio; ?>" placeholder="<?php echo $data_inicio; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
             
            </div>
            <div class="col-sm-6">
             <div class="form-group">
                <label class="control-label" for="input-status">Estado do curso</label>
                <select name="filter_estado_curso" id="input-status" class="form-control">
                  <?php if ( $estado_curso == -1 ):?>
                    <option value="1">Ativado</option>
                    <option value="-1" selected>Desativado</option>
                  <?php else: ?>
                    <option value="1" selected>Ativado</option>
                    <option value="-1">Desativado</option>
                  <?php endif; ?>
                 
                </select>
              </div>

              <div class="form-group">
                <label class="control-label" for="input-date-start">Até:</label>
                <div class="input-group date">
                  <input type="text" name="data_end_curso" value="<?php echo $data_curso_end; ?>" placeholder="<?php echo $data_fim; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Nome do curso</td>
                <td class="text-left">Completos</td>
                <td class="text-right">Pendentes</td>
                <td class="text-right">Processando</td>
                <td class="text-right">Abandonado</td>
                <td class="text-right">Cancelado</td>
                <td class="text-right">Data de Realização</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($cursos) { ?>
              <?php foreach ($cursos as $curso) { ?>
              <tr>
                <td class="text-left"><?php echo $curso['nome_curso']; ?></td>
                <td class="text-left"><?php echo $curso['completos']; ?></td>
                <td class="text-left"><?php echo $curso['pendentes']; ?></td>
                <td class="text-right"><?php echo $curso['processando']; ?></td>
                <td class="text-right"><?php echo $curso['abandonados']; ?></td>
                <td class="text-right"><?php echo $curso['cancelados']; ?></td>
                 <td class="text-right"><?php echo $curso['data_curso']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/sale_vendas&token=<?php echo $token; ?>';

  var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var data_start_curso = $('input[name=\'data_start_curso\']').val();
	
	if (data_start_curso) {
		url += '&data_start_curso=' + encodeURIComponent(data_start_curso);
	}

  var data_end_curso = $('input[name=\'data_end_curso\']').val();
	
	if (data_end_curso) {
		url += '&data_end_curso=' + encodeURIComponent(data_end_curso);
	}


	var filter_estado_curso = $('select[name=\'filter_estado_curso\']').val();
	
	if (filter_estado_curso) {
		url += '&filter_estado_curso=' + encodeURIComponent(filter_estado_curso);
	}

	location = url;
});


//--></script> 

  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>