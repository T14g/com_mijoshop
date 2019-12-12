<form id="mijoshop-search" class="navbar-form" role="search">
  <div class="input-group">
    <div class="input-group-btn">
      <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="glyphicon glyphicon-search"></i>
        <span class="caret"></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-left alerts-dropdown">
        <li class="dropdown-header"><?php echo $text_search_options; ?></li>
        <li><a onclick="setOption('catalog', '<?php echo $text_catalog_placeholder; ?>'); return false;"><i class="fa fa-book"></i><span><?php echo $text_catalog; ?></span></a></li>
        <li><a onclick="setOption('customers', '<?php echo $text_customers_placeholder; ?>'); return false;"><i class="fa fa-group"></i><span><?php echo $text_customers; ?></span></a></li>
        <li><a onclick="setOption('orders', '<?php echo $text_orders_placeholder; ?>'); return false;"><i class="fa fa-credit-card"></i><span><?php echo $text_orders; ?></span></a></li>
      </ul>
    </div>
    <input id="mijoshop-search-input" type="text" class="form-control" placeholder="Search" name="query" autocomplete="off">
    <input id="mijoshop-search-option" type="hidden" name="search-option" value="catalog">
    <div id="loader-search"><img src="view/image/loader-search.gif"></div>
  </div>
</form>
<div id="mijoshop-search-result"></div>

<script type="text/javascript">
    function setOption(option, text) {
        jQuery('#mijoshop-search-option').val(option);
        jQuery('#mijoshop-search-input').attr('placeholder', text);
    }

    jQuery('#mijoshop-search-input').keyup(function(){
        var option = jQuery('#mijoshop-search-option').val();
        var length = 3;

        if(option == 'orders') {
            length = 1;
        }

        if(this.value.length < length) {
            return false;
        }

        if(jQuery.support.leadingWhitespace == false) {
              return false;
        }

        jQuery('#loader-search').css('display', 'block');

        jQuery.ajax({
            type: 'get',
            url: 'index.php?route=search/search/search',
            data: jQuery('#mijoshop-search').serialize(),
            dataType: 'json',
            success:function(json){
                jQuery('#mijoshop-search-result').css('display', 'block');
                jQuery('#loader-search').css('display', 'none');

                if(json['error']) {
                    jQuery('#mijoshop-search-result').html(json['error'])
                    return;
                }

                jQuery('#mijoshop-search-result').html(json['result'])
            }
        });
    });

    jQuery(document).mouseup(function (e) {
        var container = jQuery('#mijoshop-search-result');

        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });
	
	jQuery('#mijoshop-search').submit(function(e) {
		e.preventDefault();
	});
</script>