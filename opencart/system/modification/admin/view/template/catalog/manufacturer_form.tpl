<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        
            <button type="submit" onclick="save('save')" form="form-manufacturer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" data-original-title="Save"><i class="fa fa-save"></i></button>
            <button type="submit" form="form-manufacturer" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-primary" data-original-title="Save & Close"><i class="fa fa-sign-out"></i></button>
            <button type="submit" onclick="save('new')" form="form-manufacturer" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-primary" data-original-title="Save & New"><i class="fa fa-sign-in"></i></button>
            
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-manufacturer" class="form-horizontal">
		          
		  <ul class="nav nav-tabs" id="language">
            <li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
            <?php if (MijoShop::get('base')->isMijosefInstalled()) { ?><li><a href="#tab-mijosef" data-toggle="tab">MijoSEF</a></li><?php } ?>
		  </ul>
		<div class="tab-content">
		  <div class="tab-pane active" id="tab-general">
		  <br />
		  
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
				  
				<div class="form-group">
				<label class="col-sm-2 control-label" for="input-alias"><?php echo JText::_('COM_MIJOSHOP_ALIAS'); ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="<?php echo JText::_('COM_MIJOSHOP_ALIAS'); ?>" name="keyword" id="input-alias" value="<?php echo $keyword; ?>" />
                </div>
				</div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <div class="checkbox">
                  <label>
                    <?php if (in_array(0, $manufacturer_store)) { ?>
                    <input type="checkbox" name="manufacturer_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="manufacturer_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php foreach ($stores as $store) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($store['store_id'], $manufacturer_store)) { ?>
                    <input type="checkbox" name="manufacturer_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="manufacturer_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
            <div class="col-sm-10"> <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
              <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

		</div>	
      <?php if (MijoShop::get('base')->isMijosefInstalled()) { ?>
	  <div class="tab-pane" id="tab-mijosef">
	  <br />
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="input-sef-url">SEF URL</label>
			<div class="col-sm-10">
				<input type="text" name="mijosef[0][url_sef]" id="input-sef-url" class="form-control" placeholder="SEF Url" value="<?php echo MijoShop::get('mijosef')->get('url_sef', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?>" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-sef-url">Meta Title</label>
			<div class="col-sm-10">
				<input type="text" name="mijosef[0][title]" class="form-control" placeholder="Meta Title" value="<?php echo MijoShop::get('mijosef')->get('title', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?>" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-sef-url">Meta Description</label>
			<div class="col-sm-10">
				<textarea class="text_area" name="mijosef[0][description]" cols="57" rows="5" placeholder="Meta Description"><?php echo MijoShop::get('mijosef')->get('description', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?></textarea>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-sef-url">Meta Keywords</label>
			<div class="col-sm-10">
				<textarea class="text_area" name="mijosef[0][keywords]" cols="57" rows="5" placeholder="Meta Keywords"><?php echo MijoShop::get('mijosef')->get('keywords', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?></textarea>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-sef-url">Meta Language</label>
			<div class="col-sm-10">
				<input type="text" name="mijosef[0][lang]" class="form-control" placeholder="Meta Language" value="<?php echo MijoShop::get('mijosef')->get('lang', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?>" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-sef-url">Meta Robots</label>
			<div class="col-sm-10">
				<input type="text" name="mijosef[0][robots]" class="form-control" placeholder="Meta Robots" value="<?php echo MijoShop::get('mijosef')->get('robots', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?>" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-sef-url">Meta Googlebot</label>
			<div class="col-sm-10">
				<input type="text" name="mijosef[0][googlebot]" class="form-control" placeholder="Meta Googlebot" value="<?php echo MijoShop::get('mijosef')->get('googlebot', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?>" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-sef-url">Meta Canonical URL</label>
			<div class="col-sm-10">
				<input type="text" name="mijosef[0][canonical]" class="form-control" placeholder="Meta Canonical URL" value="<?php echo MijoShop::get('mijosef')->get('canonical', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?>" />
			</div>
		  </div>
            <input type="hidden" name="mijosef[0][url_id]" value="<?php echo MijoShop::get('mijosef')->get('url_id', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?>" />
            <input type="hidden" name="mijosef[0][meta_id]" value="<?php echo MijoShop::get('mijosef')->get('meta_id', 'manufacturer', JRequest::getInt('manufacturer_id', 0, 'get'), 0); ?>" />
            <input type="hidden" name="mijosef[0][route_var]" value="route=product/manufacturer/info" />
            <input type="hidden" name="mijosef[0][route_id]" value="manufacturer_id=<?php echo JRequest::getInt('manufacturer_id', 0, 'get'); ?>" />
        </div>
        <?php } ?>
	</div>	
		
        </form>
      </div>
    </div>
  </div>
</div>

        <script type="text/javascript"><!--
        function save(type){
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'button';
            input.value = type;
            form = $("form[id^='form-']").append(input);
            form.submit();
        }
        //--></script>
            
<?php echo $footer; ?>