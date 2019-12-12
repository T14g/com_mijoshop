<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>

<script>var urlSSL = '<?php echo HTTPS_SERVER; ?>';</script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>

<!-- scripts e fonts checkout padrão via cdn -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />

<?php if(!empty($custom_tema)){ ?>
<!-- custom bootstrap -->
<link rel="stylesheet" href="<?php echo $custom_tema;?>">
<?php } ?>

<!-- css -->
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>

<!-- javascript -->
<script src="components/com_mijoshop/opencart/catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>

<?php echo $google_analytics; ?>

<?php if(isset($facebook_ativado) && $facebook_ativado){ ?>
<!-- script facebook login -->
<script>
	window.fbAsyncInit = function() {
	FB.init({
	appId      : '<?php echo @$facebook_id;?>',
	xfbml      : true,
	version    : 'v2.4'
	});
	};
	(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/pt_BR/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	function LoginFB(login){
	FB.login(function(response) {
	console.log(response);
	if (response.status === 'connected') {
	var SignFbResquest = response.authResponse.signedRequest;
	FB.api('/me?fields=id,first_name,last_name,email', function(response) {
	console.log(response);
	if (typeof response.email != 'undefined') {
	if(login==true){
	validarEmailFacebook(response.email,SignFbResquest,response.id,response.first_name,response.last_name);
	}else{
	validarEmailFacebook2(response.email,SignFbResquest,response.id,response.first_name,response.last_name);
	}
	}
	});
	} else if (response.status === 'not_authorized') {
	if(login==true){
	$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Ops, acesso ao facebook n&atilde;o foi autorizado!<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
	}else{
	$('#div-login-msg-aviso').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Ops, acesso ao facebook n&atilde;o foi autorizado!<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
	}
	}}, {scope: 'public_profile,email'});
	}

	function validarEmailFacebook(email,sign,user,nome,sobrenome){
	console.log('fb login');
	$.ajax({
	type: "POST",
	url: "index.php?route=account/registroexpress/entrarfb",
	dataType: 'json',
	data: {email:email,sign:sign,user:user}
	}).done(function( dados ) {
	if(dados.erro==false){
	location.href = dados.href;	
	return true;
	}else{
	var url_cadastro = 'index.php?route=account/registroexpress&fb='+email+'|'+nome+'|'+sobrenome+'';
	$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Ops, '+email+' n&atilde;o cadastrado na loja! <a href="'+url_cadastro+'">Clique aqui</a> e cadastre-se.<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
	return false;	
	}
	});
	}

	function validarEmailFacebook2(email,sign,user,nome,sobrenome){
	console.log('fb empress');
	$.ajax({
	type: "POST",
	url: "index.php?route=account/registroexpress/entrarfb",
	dataType: 'json',
	data: {email:email,sign:sign,user:user}
	}).done(function( dados ) {
	if(dados.erro==false){
	location.reload(true);
	}else{
	$('#div-login-msg-aviso').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Ops, '+email+' n&atilde;o cadastrado na loja!<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
	return false;	
	}
	});
	}
	</script>
<?php } ?>

</head>
<body class="<?php echo $class; ?>">

<nav id="top">
  <div class="container">
    <?php echo $currency; ?>
    <?php echo $language; ?>
     <div id="top-links" class="nav pull-right">
      <ul class="list-inline">
        <li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
        <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($logged) { ?>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <?php } ?>
          </ul>
        </li>
        <li><a href="<?php echo $wishlist; ?>" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
        <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
        <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
      </ul>
    </div>
  
  </div>
</nav>
<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" id="logo-marca" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
      <div class="col-sm-5"></div>
      <div class="col-sm-4"><?php echo $cart;?></div>
    </div>
  </div>
</header>
