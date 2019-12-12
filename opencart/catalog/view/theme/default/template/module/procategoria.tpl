<h3><?php echo $heading_title; ?></h3>

<div class="row">
  <?php foreach ($products as $product) { ?>

  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="product-thumb transition procategoria">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>

      <div class="caption" style="min-height: 70px">
        <h4 style="margin-top: 25px"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>      

      </div>

      <div class="destaque-bt">
        <a href="<?php echo $product['href']; ?>" class="inscri">
          <button type="button" class="btn btn-success">
            <i class="fa fa-share"></i> <span> Saiba Mais</span>
          </button>
        </a>

      </div>

    </div>

  </div>

  <?php } ?>

</div>

