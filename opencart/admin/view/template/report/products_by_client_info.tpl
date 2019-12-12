<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
		<h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
		<div class="buttons"><a href="<?php echo $return; ?>" class="button"><?php echo $button_back; ?></a></div>
		<div class="buttons"><a href="<?php echo $url_export; ?>" target="_blank" class="button"><?php echo $button_export; ?></a></div>
    </div>
    <div class="content">
	<?php if (isset($customerProduct)) { ?>
		<table class="form">
			<tbody>
				<tr>
					<td><?php echo $text_name; ?></td>
					<td><a href="<?php echo $url_customer; ?>" target="blank"><?php echo $customerProduct['fullname']; ?></a></td>
				</tr>
				<tr>
					<td><?php echo $text_email; ?></td>
					<td><?php echo $customerProduct['email']; ?></td>
				</tr>
				<tr>
					<td><?php echo $text_phone; ?></td>
					<td><?php echo $customerProduct['telephone']; ?></td>
				</tr>
				<tr>
					<td><?php echo $text_product; ?></td>
					<td>
					<?php if($customerProduct['product_name']) { ?>
						<a href="<?php echo $url_product; ?>" target="blank"><?php echo $customerProduct['product_name']; ?></a>
					<?php }else{ ?>
						<?php echo $text_product_removed; ?>
					<?php } ?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $txt_column_id_order; ?></td>
            <td class="left"><?php echo $txt_column_payment_method; ?></td>
            <td class="left"><?php echo $txt_column_situation; ?></td>
            <td class="left"><?php echo $txt_column_total; ?></td>
            <td class="left"><?php echo $txt_column_date_added; ?></td>
            <td class="left"><?php echo $txt_column_date_modified; ?></td>
            <td class="left"><?php echo $txt_column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($orders)) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr>
            <td class="left"><?php echo $order['order_id']; ?></td>
            <td class="left"><?php echo $order['payment_method']; ?></td>
            <td class="left"><?php echo (isset($all_statuses[$order['order_status_id']]) ? $all_statuses[$order['order_status_id']] : $text_status_not_found); ?></td>
            <td class="left"><?php echo $this->currency->format($order['total']); ?></td>
            <td class="left"><?php echo date($this->language->get('date_format_short').' H:i:s', strtotime($order['date_added'])); ?></td>
            <td class="left"><?php echo date($this->language->get('date_format_short').' H:i:s', strtotime($order['date_modified'])); ?></td>
            <td class="right"><a href="<?php echo $url_order.'&order_id='.$order['order_id']; ?>" target="blank"><?php echo $text_view_order; ?></a></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
	  <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>