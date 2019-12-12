<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2 style="display: none"><?php echo $heading_title; ?></h2>
      <?php if ($thumb || $description) { ?>
      <div class="row">
        <?php if ($thumb) { ?>
        <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
        <?php } ?>
        <!-- Modificação -->
        
        <div class="col-sm-7 filtros">
          <p style="font-size: 16px;color: #004ea3;">Inscreva-se em uma de nossas turmas.<br/>Filtrar turmas por:</p>
          <div class="row">
          <form action="" method="post" id="filtrar">
          <div class="col-sm-6 col-xs-12">
          <div class="input-group" style="margin: 5px 0px">
            <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-map-marker"></i></span>
            <select name="local" class="form-control" aria-describedby="sizing-addon1">
              <?php if(!empty($_POST['local'])){ ?>
                <option value="">Todos os Locais</option>
              <?php } else { ?>
                <option value="">Locais</option>
              <?php } ?>
              <?php foreach($novo as $valor){  ?>
              <option value="<?php echo $valor['id']; ?>"><?php echo $valor[0]; ?></option>
              <?php } ?>
          </select>
          </div></div>
          <div class="col-sm-6 col-xs-12">
          <div class="input-group" style="margin: 5px 0px">
          <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-calendar"></i></span>
          <select name="data_curso" class="form-control" aria-describedby="sizing-addon1">
          <?php if(!empty($_POST['data_curso'])){ ?>
              <option value="">Todas as data</option>
          <?php } else { ?>
              <option value="">Datas</option>
          <?php } ?>
        <?php foreach ($nova_data as $campoData) { ?>
          <option value="<?php echo $campoData['data_nova']; ?>">
          <?php setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
          echo utf8_encode(strftime('%B de %Y', strtotime("01-".$campoData['data_nova']))); ?>
        </option>
        <?php } ?>
        </select>
         </div>  </div> 
           
        </form>
        </div>
        <!-- Button trigger modal -->
        <div class=" btn btn-primary filtrar desc_curso">
          <button type="button" data-toggle="modal" data-target="#myModal">Descrição</button>
        </div>
        <button form="filtrar" class="btn btn-primary filtrar" style="margin: 5px 0px;padding: 7px 17px">Filtrar</button>
    </div>
     
      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <?php if ($description) { ?>
          <div class="modal-content">
            <div class="modal-body">
            <?php echo MijoShop::get('base')->triggerContentPlg($description); ?> 
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
          </div>
          <?php } ?>
        </div>
  </div>

      </div>
      <hr>
      <?php } ?>
      <?php if ($categories) { ?>
      <h3><?php echo $text_refine; ?></h3>
      <?php if (count($categories) <= 5) { ?>
      <div class="row">
        <div class="col-sm-9">
          <ul>
            <?php foreach ($categories as $category) { ?>
			<li><?php if ($category['thumb']) { ?>
				<a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" /></a>
				</br>
			<?php } ?>
            <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
      <?php } else { ?>
      <div class="row">
        <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
        <div class="col-sm-9">
          <ul>
            <?php foreach ($categories as $category) { ?>
            <li><?php if ($category['thumb']) { ?>
				<a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" /></a>
				</br>
			<?php } ?><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      </br>
      <?php } ?>
      <?php if ($products) { ?>
      <p><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
      <div style="display: none;" class="row">
        <div class="col-md-4">
          <div class="btn-group hidden-xs">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-2 text-right">
          <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
        </div>
        <div class="col-md-3 text-right">
          <select id="input-sort" class="form-control" onchange="location = this.value;">
            <?php foreach ($sorts as $sorts) { ?>
            <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
            <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-1 text-right">
          <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
        </div>
        <div class="col-md-2 text-right">
          <select id="input-limit" class="form-control" onchange="location = this.value;">
            <?php foreach ($limits as $limits) { ?>
            <?php if ($limits['value'] == $limit) { ?>
            <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <br />
      <div class="row">
        <?php foreach ($products as $product) { 
          $date = new DateTime($product['date_added']);
          $data_curso = $date->format('Y-m-d');
        ?>
        <?php if($data_curso > date('Y-m-d')){ ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb panel panel-default">
            <div class="panel-heading">
              <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
            </div>
            <div>
              <div class="panel-body">
              <div style="display: none;" class="col-md-3 col-xs-6">
              <?php if($product['model'] != "Combos"){ ?>
                <span class="curso_data"><i class="fa fa-calendar"></i>
                <?php setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                echo utf8_encode(strftime('%d de %B de %Y', strtotime($product['date_added']))); ?>
                </span>
              <?php } else { ?>
                <span class="curso_data"><i class="fa fa-calendar"></i> A definir</span>
              <?php } ?>
              </div>
              <?php if ($product['price']) { ?>
              <div class="price col-md-3 col-xs-6"><i class="fa fa-credit-card"></i> 
                <?php if (!$product['special']) { ?>
                <span class="<?php echo $product['model']; ?>"><?php echo $product['price']; ?></span>
                <?php } else { ?>
                <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                <?php } ?>
                <?php if ($product['tax']) { ?><br />
                <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                <?php } ?>
              </div>
              <?php } ?>
                 <?php if ($product['rating']) { ?>
                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
                <div class="local col-md-3 col-xs-6"><i class="fa fa-map-marker"></i>
                  <?php echo $product['manufacturer']; ?>
                </div>
                 <div class="local col-md-3 col-xs-6">
                <button style="background: transparent; border: none;font-size:12px;color: #004ea3;margin-top: 5px" data-toggle="collapse" data-target="#description<?php echo $product['product_id']; ?>" ><i class="fa fa-navicon"></i> Informações</button>
                </div>
                <div class="local col-md-3 col-xs-6">
                  <button class="btn btn-success btn-block" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class=""><?php echo $button_cart; ?></span></button>
                  <div style="display: none" class="button-group">
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
                  </div>
                </div>
                <div id="description<?php echo $product['product_id']; ?>" class="collapse desc ajuste">
                  <?php echo $product['description']; ?>
                </div> 

              </div>
              
            </div>
          </div>
        </div>
        <?php } } ?>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
    <div class="alert" style="background: #f7f7f7;border: solid 1px #ddd;">		<?php echo MijoShop::get('base')->triggerContentPlg($description); ?>
    </div>
</div>
<?php echo $footer; ?>
<?php $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
$position = 1;
?>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "ItemList",
  "itemListElement": [
  <?php foreach ($products as $product) { ?>
    {
      "@type": "ListItem",
      "position": "<?php echo $position++; ?>",
      "item": {
        "@type": "Course",
        "url": "<?php echo $url; ?>#<?php echo $product['product_id']; ?>",
        "provider": "Organization",
        "name": "<?php echo $product['name']; ?>",
        "image": "<?php echo $product['thumb']; ?>",
        "description": "<?php echo $product['description']; ?>"       
      }
    },
    <?php } ?>
{
      "@type": "ListItem", 
      "position": 99,     
      "item": {
        "@type": "Course",
        "provider": "Organization",
        "url": "<?php echo $url; ?>",
        "name": "<?php echo $heading_title; ?>",        
        "description": "<?php echo strip_tags($description); ?>"       
      }
    }
    
  ]
}
</script>