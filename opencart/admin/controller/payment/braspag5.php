<?php
//Loja5.com.br
if(version_compare(PHP_VERSION, '5.4.0', '<')) {
	include(DIR_SYSTEM."../app/braspag5/include/php53/braspag5.php");
}elseif(version_compare(PHP_VERSION, '5.5.0', '<')) {
	include(DIR_SYSTEM."../app/braspag5/include/php54/braspag5.php");
}elseif(version_compare(PHP_VERSION, '5.6.0', '<')){
	include(DIR_SYSTEM."../app/braspag5/include/php55/braspag5.php");
}elseif(version_compare(PHP_VERSION, '7.1.0', '<')){
	include(DIR_SYSTEM."../app/braspag5/include/php56/braspag5.php");
}elseif(version_compare(PHP_VERSION, '7.2.0', '<')){
	include(DIR_SYSTEM."../app/braspag5/include/php71/braspag5.php");
}else{
	include(DIR_SYSTEM."../app/braspag5/include/php72/braspag5.php");
}
//Loja5.com.br
?>