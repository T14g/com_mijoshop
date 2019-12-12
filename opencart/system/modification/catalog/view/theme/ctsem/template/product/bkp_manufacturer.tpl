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
      <?php if ($products) { ?>
      
      <div style="display: none" class="row">
        <div class="col-sm-3">
          <div class="btn-group hidden-xs">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-sm-1 col-sm-offset-2 text-right">
          <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
        </div>
        <div class="col-sm-3 text-right">
          <select id="input-sort" class="form-control col-sm-3" onchange="location = this.value;">
            <?php foreach ($sorts as $sorts) { ?>
            <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
            <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-1 text-right">
          <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
        </div>
        <div class="col-sm-2 text-right">
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
        <?php foreach ($products as $product) { ?>
        <?php 
          $date = new DateTime($product['date_added']);
          $data_curso = $date->format('Y-m-d');
          if($data_curso > date('Y-m-d')){ 
        
        ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb panel panel-default">
            <div class="panel-heading">
              <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
            </div>
            <div>
            <div class="panel-body">
              <?php if ($product['price']) { ?>
              <div class="<?php echo $product['model']; ?> price col-md-3 col-xs-6"><i class="fa fa-credit-card"></i> 
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
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
                <div class="local col-md-3 col-xs-6">
                <button style="background: transparent; border: none;font-size:12px;color: #004ea3;margin-top: 5px" data-toggle="collapse" data-target="#description<?php echo $product['product_id']; ?>" ><i class="fa fa-navicon"></i> Informações</button>
                </div>
                <div class="local col-md-3 col-xs-6">
                  <button class="btn btn-success btn-block" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class=""><?php echo $button_cart; ?></span></button>
                  <div style="display: none" class="button-group">
                  
                  
                  </div>
                </div>
                <div id="description<?php echo $product['product_id']; ?>" class="collapse desc ajuste">
                  <?php echo $product['description']; ?>
                </div>
              </div>
            </div>
          </div><!-- panel default -->
        </div><!-- product layout -->
        <?php }  } ?>
      </div><!-- row -->
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } else { ?>
      <h3>Ainda não temos turmas para esta região mas você pode organizar sua turma 
<a href="/promova">Clicando Aqui</a>. <br/><br/>Mais informações envie um e-mail para secretaria@ctsem.com ou uma mensagem pelo WhatsApp (51) 9 9585-1265.</h3>
      
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
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
        "description": "Cursos deste desta localidade."       
      }
    }
    
  ]
}
</script>