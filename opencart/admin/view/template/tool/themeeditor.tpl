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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" form="form-backup" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange"></i> <?php echo $heading_title; ?></h3>
		<div class="pull-right">
			<a onclick="clearAllCache();" id="clear" class="btn btn-default"><?php echo $button_clear_cache; ?></a> 
			<a href="index.php?option=com_mijoshop&route=tool/themeeditor" class="btn btn-default ">New Theme</a>
		</div>
      </div>
      <div class="panel-body">
      <table>
		<tr>
			<td valign="top" style="width: 240px;"><div id="column-left-theme" class="fileTree"></div></td>
			<td valign="top" style="margin-left: 10px;">
				<div id="message" class="attention"></div>
				<div id="display_file" style="margin: 0px 10px 0px 10px;">
				  <br/>
				  <div>
                      <table>
                          <tr>
                            <td style="vertical-align: middle;">
                                New Theme Name:&nbsp;&nbsp;<input type="text" id="new_name" style="width: 150px;">&nbsp;&nbsp;&nbsp;<a onclick="folderCreate()" id="clone_theme" class="button btnX">Create</a>
                            </td>
                          </tr>
                      </table>
                  </div>
			      <br/><br/>
				  <?php if ($text_folder_no_writable) { ?>
				  <div class="warning"><?php echo $text_folder_no_writable; ?></div>
				  <?php } ?>
				  <div class="attention" id="sfb"><?php echo $entry_backup_files; ?> <?php echo $size; ?></div>
				  <table class="form">
					<tr>
					  <td><?php echo $entry_last_backup_files; ?></td>
					  <td>
					  <?php foreach ($files as $file_cache) {
					    echo $file_cache['date'] . ' - ' . $file_cache['name'] . ' (' . $file_cache['size'] . ')<br />';
					  } ?>
					  </td>
				    </tr>
				  </table>
				</div>
				<div id="loading"><img src="view/image/loading.gif" /></div>
			</td>
		</tr>
      </table>
      </div>
    </div>
  </div>
</div>

<link type="text/css" href="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/css/codemirror.css" rel="stylesheet">
<link type="text/css" href="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/css/dialog.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/filetree/jqueryFileTree.css" media="screen" />
<script type="text/javascript" src="view/javascript/jquery/filetree/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="view/javascript/jquery/filetree/jqueryFileTree.js"></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/codemirror.js" type="text/javascript"></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/javascript.js" type="text/javascript"></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/xml.js" type="text/javascript"></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/css.js" type="text/javascript"></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/php.js" type="text/javascript"></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/matchbrackets.js" type="text/javascript"></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/matchtags.js" type="text/javascript"></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/htmlmixed.js" type="text/javascript"></script>

<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/dialog.js" type="text/javascript" ></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/searchcursor.js" type="text/javascript" ></script>
<script src="../plugins/system/mijoshopjquery/mijoshopjquery/codemirror/js/search.js" type="text/javascript" ></script>
<style type="text/css">
.fileTree {
	height: 670px;
	border-top: solid 1px #BBB;
	border-left: solid 1px #BBB;
	border-bottom: solid 1px #FFF;
	border-right: solid 1px #FFF;
	background: #FFF;
	overflow: scroll;
	padding: 5px;
	width: 240px;
}

.CodeMirror-line-numbers {
	font-size: 10pt;
	margin: 0.4em;
	padding-right: 0.4em;
	text-align: right;
	background: #FAF0E6;
}

.CodeMirror pre {
	display:inline-block;
	padding-left: 7px;
	line-height: 2;
}

.CodeMirror {
	font-size: 14px !important;
	height: 500px !important;
    width: 100% !important;
}

input {
	height: 20px;
	font-size: 14px;
}

#files_cache{
	margin-bottom: 0 !important;
}

.buttons{
	height: 30px;
}

.btnX{
	-moz-border-bottom-colors: none;
	-moz-border-image: none;
	-moz-border-left-colors: none;
	-moz-border-right-colors: none;
	-moz-border-top-colors: none;
	background-color: #F5F5F5;
	background-image: -moz-linear-gradient(center top , #FFFFFF, #E6E6E6);
	background-repeat: repeat-x;
	border-color: #BBBBBB #BBBBBB #A2A2A2;
	border-radius: 4px 4px 4px 4px;
	border-style: solid;
	border-width: 1px;
	box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
	color: #333333;
	cursor: pointer;
	font-size: 13px;
	line-height: 18px;
	margin-bottom: 0;
	margin-right: 10px !important;
	padding: 4px 12px;
	text-align: center;
	text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
	vertical-align: middle;
	height: 25px !important;
}

.cancel {
	margin-left: 15px;
}

input{
	margin-bottom: 0 !important;
	margin-right: 10px;
	height: 19px !important;
}

select {
	height: 23px !important;
}

#new_name{
	margin-right: 0 !important;
}

table {width: 100%; } 
table.info { border-collapse: collapse;  }
table.info td { padding: 0 10px 10px 10px; color: #000000; }
table.info td.right { text-align: right; }
#loading, #message { display: none; }
</style>
<script type="text/javascript"><!--
jQuery(document).ready(function () {
	jQuery('#column-left-theme').fileTree({
        script: 'index.php?option=com_mijoshop&route=tool/themeeditor/folder&token=<?php echo $token; ?>',
        folderEvent: 'click',
        expandSpeed: 750,
        collapseSpeed: 750,
        multiFolder: false
    });
});

CodeMirror.prototype.getCode = function() {
    $('.success, .warning, .wait').remove();
	$('#message').html('<?php echo $text_wait; ?>').show();

    var  path_file =  document.getElementById('path_file').value;
    var  edit_file =  document.getElementById('edit_file').value;

	$.ajax({
		type: "POST",
		url: "index.php?route=tool/themeeditor/save&token=<?php echo $token; ?>",
		data: {templates: this.doc.getValue(), path_file: path_file, edit_file: edit_file},
		dataType: 'json',
		success: function(json) {
			$('#message').html('').hide();
			//$('#display_file').empty().hide();

			if (json['success']) {
				$('.breadcrumb').after('<div class="success">' + json['success'] + '</div>');
				getBackupFiles($('input[name=\'edit_file\']').val());
			}

			if (json['error']) {
				$('.breadcrumb').after('<div class="warning">' + json['error'] + '</div>');
			}
		}
	});
};
CodeMirror.prototype.find = function() {
    CodeMirror.commands.find(this);
};

CodeMirror.prototype.replace = function() {
    CodeMirror.commands.replace(this);
};

function tpl_code_mirror(extension) {
	var textarea = document.getElementById('code_mirror');

	if(extension == "css") {
		var editor = CodeMirror.fromTextArea(textarea, {
			content: textarea.value,
			lineNumbers: true,
            lineWrapping: true,
            mode:"text/css"
		});
	} else if(extension == "js") {
		var editor = CodeMirror.fromTextArea(textarea, {
            lineNumbers: true,
            lineWrapping: true,
            styleActiveLine: true,
            matchBrackets: true,
            mode:"text/javascript"
		});
	} else {
		var editor = CodeMirror.fromTextArea(textarea, {
            lineNumbers: true,
            lineWrapping: true,
            styleActiveLine: true,
            matchBrackets: true,
            mode:"text/html"
		});
	}

    this.home = document.createElement("DIV");

    if (textarea.appendChild)
        textarea.appendChild(this.home);
    else
        textarea(this.home);

    var self = editor;

    function makeButton(name, action) {
        var button = document.createElement("INPUT");
        if(action == 'search_oc'){
            action = 'find';
        }

        button.type = "button";
        button.value = name;
        button.className = 'button btnX'
        document.getElementById('ds').appendChild(button);
        button.onclick = function(){
            self[action].call(self);
        };
    }

    makeButton("Search", "find");
    makeButton("Replace", "replace");
    makeButton("Save", "getCode");
}

function getBackupFiles(file) {
	if (file == '')
		return;

	$.ajax({
		type: "POST",
		url: "index.php?route=tool/themeeditor/getbackupfiles&token=<?php echo $token; ?>",
		data: "&file=" + file,
		dataType: 'json',
		success: function(json){
			if (json['files']) {
				var p = json['files'];
				var html = '<?php echo $entry_available_backups; ?> <select name="files_cache" id="files_cache"  style="margin-right: 10px;">';

				for (var key in p) {
					if (p.hasOwnProperty(key)) {
						html += '<option value="' + p[key].id + '">' + p[key].name + ' (' + p[key].size + ')</option>';
					}
				}

				html += '</select>';
				html += '<a id="button-restore" class="button btnX"><span><?php echo $button_restore; ?></span></a> <a id="button-delete-cache" class="button btnX"><span><?php echo $button_delete; ?></span></a>';

				document.getElementById('backups').innerHTML = html;
			}
		}
	});
}

function tpl_edit(path_file, file, extension) {
	$('.success, .warning, #message').hide();
	$('.content').css('background', 'none');
	$('#display_file').empty();
	$('#loading').show();

	$.ajax({
		type: "POST",
		url: "index.php?route=tool/themeeditor/edit&token=<?php echo $token; ?>",
		data: "&path_file=" + path_file + "&file=" + file,
		success: function(msg){
			$('#display_file').html(msg);
			$('#loading').hide();
			tpl_code_mirror(extension);
			$('#display_file').fadeIn('fast');
		}
	});

	return false;
}

function tpls_restore(id, path_file, edit_file) {
	$('#display_file').empty().hide();
	$('#message').hide();

	$.ajax({
		type: "POST",
		url: "index.php?route=tool/themeeditor/restore&token=<?php echo $token; ?>",
		data: "&id=" + id + "&path_file=" + path_file + "&edit_file=" + edit_file,
		success: function(msg){
			$('#message').html(msg).show();

			setTimeout(function(){
				$('#message').hide();
            }, 2500);
		}
	});

	return false;
}

function folderCreate() {
	$.ajax({
		type: "POST",
		url: "index.php?route=tool/themeeditor/folderCreate&token=<?php echo $token; ?>",
		data: "&name="+ $('#new_name').val(),
		success: function(msg){
            window.location = 'index.php?route=tool/themeeditor';
		}
	});

	return false;
}

function cloneFile(curr_path, file){
    var opt = document.getElementById('theme_name');

    jQuery.ajax({
   		type: "POST",
   		url: "index.php?option=com_mijoshop&route=tool/themeeditor/cloneFile",
   		data: "&path="+ $("#theme_name").val() + "&curr_path="+ curr_path + "&file="+file,
   		success: function(msg){
               window.location = 'index.php?route=tool/themeeditor';
   		}
   	});

   	return false;
}

function tpls_delete_cache(id) {
	$('.success, .warning, .wait').remove();
	$('#message').hide();

	$.ajax({
		type: "POST",
		url: "index.php?route=tool/themeeditor/delete&token=<?php echo $token; ?>",
		data: "&file_id=" + id,
		dataType: 'json',
		success: function(json){
			if (json['success']) {
				$('.breadcrumb').after('<div class="success">' + json['success'] + '</div>');
				$('select[name=\'files_cache\'] option:selected').remove();

				if ($('select[name=\'files_cache\'] option').length <= 0) {
					$('table.info tr:eq(0)').remove();
				}
			}

			if (json['error']) {
				$('.breadcrumb').after('<div class="warning">' + json['error'] + '</div>');
			}

			setTimeout(function(){
				$('.warning, .success').hide();
            }, 2500);
		}
	});

	return false;
}

function clearAllCache() {
	if (!confirm('Are you sure you want to do this?')) {
		return false;
	}

	$('.success, .warning, .wait').remove();
	$('#clear').html('<img src="view/image/loading.gif" alt="" />');

	$.getJSON("index.php?route=tool/themeeditor/clearcache&token=<?php echo $token; ?>", function(result) {
		$('.wait').remove();

		if (result.error) {
			$('.breadcrumb').after('<div class="warning">' + result.error + '</div>');
		} else {
			$('.breadcrumb').after('<div class="success">' + result.success + '</div>');

			$('table.form:eq(1) td:eq(1)').html('');
			$('#sfb').html('<?php echo $entry_backup_files; ?> 0');
		}

		$('#clear').html('<?php echo $button_clear_cache; ?>');
	});
}

$('#button-restore').live('click', function() {
    var files_cache = document.getElementById('files_cache');
    var path_file = document.getElementById('path_file');
    var edit_file = document.getElementById('edit_file');

	tpls_restore(files_cache.value, path_file.value, edit_file.value);
});

$('#button-delete-cache').live('click', function() {
	tpls_delete_cache(document.getElementById('files_cache').value);
});
//--></script>
<?php echo $footer; ?>