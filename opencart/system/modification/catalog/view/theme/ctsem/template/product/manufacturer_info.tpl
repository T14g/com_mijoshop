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

    <div class="row-correct-courses">

    <div id="content_oc" class="<?php echo $class; ?>"><?php echo $content_top; ?>
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

       <!-- .courses--box -->
        <div class="courses-box">
      
        <?php foreach ($products as $product) { ?>
        <?php 
          $date = new DateTime($product['date_added']);
          $data_curso = $date->format('Y-m-d');
          if($data_curso > date('Y-m-d')){ 
        ?>

        <!-- Single course -->
        <div class="course-single">
          <p class="course-title">
            <a href="<?php echo $product['href']; ?>">
            <svg class="svg-pin"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999"><path d="M454.848 198.848c0 159.225-179.751 306.689-179.751 306.689-10.503 8.617-27.692 8.617-38.195 0 0 0-179.751-147.464-179.751-306.689C57.153 89.027 146.18 0 256 0s198.848 89.027 198.848 198.848z" fill="#A4061E"/><path d="M256 298.89c-55.164 0-100.041-44.879-100.041-100.041S200.838 98.806 256 98.806s100.041 44.879 100.041 100.041S311.164 298.89 256 298.89z" fill="#ffe1d6"/></svg>
            <?php echo $product['name']; ?>
            </a>
          </p>

          <p class="course-date">
              <span class="font-date"><i class="fa fa-calendar"></i> Início:</span>
              <?php if($product['model'] != "Combos"){ ?>
              <?php setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
              echo utf8_encode(strftime('%d de %B de %Y', strtotime($product['date_added']))); ?>
            <?php } else { ?>
                A definir
            <?php } ?>
          </p>

          <button class="btn btn-success btn-sign-up" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class=""><?php echo $button_cart; ?></span></button>


          <div class="item-options">
                  <a href="<?php echo $product['href']; ?>" class="btn btn-more">
                      <svg xmlns="http://www.w3.org/2000/svg" class="svg-plus" viewBox="0 0 510 510" width="510" height="510"><path d="M255 0C114.75 0 0 114.75 0 255s114.75 255 255 255 255-114.75 255-255S395.25 0 255 0zm127.5 280.5h-102v102h-51v-102h-102v-51h102v-102h51v102h102v51z"/></svg>
                      INFORMAÇÕES
                  </a>
                  <span class="label-valor">
                      <?php if (!$product['special']) { ?>
                      <span class="<?php echo $product['model']; ?>"><?php echo $product['price']; ?></span>
                      <?php } else { ?>
                      <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                      <?php } ?>
                      <?php if ($product['tax']) { ?><br />
                      <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                      <?php } ?>
                      <svg class="svg-credit" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 548.176 548.176" width="548.176" height="548.176"><path d="M534.754 68.238c-8.945-8.945-19.698-13.417-32.258-13.417H45.681c-12.562 0-23.313 4.471-32.264 13.417C4.471 77.185 0 87.936 0 100.499v347.173c0 12.566 4.471 23.318 13.417 32.264 8.951 8.946 19.702 13.419 32.264 13.419h456.815c12.56 0 23.312-4.473 32.258-13.419 8.945-8.945 13.422-19.697 13.422-32.264V100.499c0-12.563-4.477-23.314-13.422-32.261zm-23.127 379.441c0 2.478-.903 4.613-2.711 6.427-1.807 1.8-3.949 2.703-6.42 2.703H45.681c-2.473 0-4.615-.903-6.423-2.71-1.807-1.813-2.712-3.949-2.712-6.427V274.088h475.082v173.591zm0-283.23H36.545v-63.954c0-2.474.902-4.611 2.712-6.423 1.809-1.803 3.951-2.708 6.423-2.708h456.815c2.471 0 4.613.901 6.42 2.708 1.808 1.812 2.711 3.949 2.711 6.423v63.954h.001z"/><path d="M73.092 383.719h73.089v36.548H73.092zM182.728 383.719h109.634v36.548H182.728z"/></svg>
                  </span>
              </div>

        </div> <!-- end of single course-->

        <?php }  } ?>
        </div>
         <!-- end .courses--box -->

      </div><!-- row do box-->
      </div>
      
    

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