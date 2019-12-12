<?php echo $header; ?>
<div class="courses-container-list container">
  <ul class="breadcrumb">
  </ul>
  <div class="row row-correct-courses"><?php echo $column_left; ?>
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
      <div class="row row-top-filter">
        <?php if ($thumb) { ?>
        <div class="col-sm-2 col-title">
          <p class="title-agende">Agende-se</p>
        </div>
        <?php } ?>
        <!-- Modificação -->
        
        <div class="col-sm-7 filtros">
          <div class="row">
          <form action="" method="post" id="filtrar">
          <div class="col-sm-6 col-xs-12 col-cities">
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
          <div class="col-sm-6 col-xs-12 col-dates">
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
          

         </div>  
         
         </div> 
           
        </form>
        </div>

        
        <!-- Button trigger modal -->
    </div>
    <div class="col-sm-3 col-filter-btn">
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
        
        <!-- .courses--box -->
        <div class="courses-box">
        

        <?php foreach ($products as $product) { 
          $date = new DateTime($product['date_added']);
          $data_curso = $date->format('Y-m-d');
        ?>
        <?php if($data_curso > date('Y-m-d')){ ?>
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

               <button class="btn btn-success btn-sign-up" type="button" >
               	<i class="fa fa-shopping-cart"></i> 
               	<span class="">
               		<a style="color: #fff" href="<?php echo $product['href']; ?>" >
               		<?php echo $button_cart; ?></span>
               		</a>
               </button>
               
               <!--
              <button class="btn btn-success btn-sign-up" type="button" onclick="cart.add('<?php //echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class=""><?php //echo $button_cart; ?></span></button>
				-->
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
           </div>
  
        <?php } } ?>
        </div>
         <!-- end .courses--box -->

      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right text-results"><?php echo $results; ?></div>
      </div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>   
      <p class="aviso-user-cursos"><?php echo $text_empty; ?></p>
      <div class="buttons btns-results">
        <div><a href="<?php echo $continue; ?>" class="btn btn-primary btn-continue"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
    <div class="alert" id="mais-informacoes" style="background: #f7f7f7;border: solid 1px #ddd;">		<?php echo MijoShop::get('base')->triggerContentPlg($description); ?>
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
        "description": "<?php echo strip_tags($product['description']); ?>"       
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