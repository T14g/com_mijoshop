<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
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
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control"/>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control"/>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-ip"><?php echo $entry_ip; ?></label>
                                <input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" placeholder="<?php echo $entry_ip; ?>" id="input-ip" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-keyword"><?php echo $entry_keyword; ?></label>
                                <input type="text" name="filter_keyword" value="<?php echo $filter_keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control"/>
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td class="text-left"><?php echo $column_keyword; ?></td>
                            <td class="text-left"><?php echo $column_products; ?></td>
                            <td class="text-left"><?php echo $column_category; ?></td>
                            <td class="text-left"><?php echo $column_customer; ?></td>
                            <td class="text-left"><?php echo $column_ip; ?></td>
                            <td class="text-left"><?php echo $column_date_added; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($searches) { ?>
                        <?php foreach ($searches as $search) { ?>
                        <tr>
                            <td class="text-left"><?php echo $search['keyword']; ?></td>
                            <td class="text-left"><?php echo $search['products']; ?></td>
                            <td class="text-left"><?php echo $search['category']; ?></td>
                            <td class="text-left"><?php echo $search['customer']; ?></td>
                            <td class="text-left"><?php echo $search['ip']; ?></td>
                            <td class="text-left"><?php echo $search['date_added']; ?></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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
    $('#button-filter').on('click', function () {
        url = 'index.php?route=report/customer_search&token=<?php echo $token; ?>';

        var filter_date_start = $('input[name=\'filter_date_start\']').val();

        if (filter_date_start) {
            url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
        }

        var filter_date_end = $('input[name=\'filter_date_end\']').val();

        if (filter_date_end) {
            url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
        }

        var filter_keyword = $('input[name=\'filter_keyword\']').val();

        if (filter_keyword) {
            url += '&filter_keyword=' + encodeURIComponent(filter_keyword);
        }

        var filter_customer = $('input[name=\'filter_customer\']').val();

        if (filter_customer) {
            url += '&filter_customer='+ encodeURIComponent(filter_customer);
        }

        var filter_ip = $('input[name=\'filter_ip\']').val();

        if (filter_ip) {
            url += '&filter_ip=' + encodeURIComponent(filter_ip);
        }

        location = url;
    });
    //--></script>
    <script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: false
    });
    //--></script>
    <script type="text/javascript"><!--
    $('input[name=\'filter_customer\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url     : 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success : function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item['name'],
                            value: item['customer_id']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            $('input[name=\'filter_customer\']').val(item['label']);
        }
    });
    //--></script>
</div>
<?php echo $footer; ?>