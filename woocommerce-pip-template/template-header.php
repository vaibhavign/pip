<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php _e('Print order details', 'woocommerce-pip'); ?></title>
	<link href="<?php echo woocommerce_pip_template('uri', 'template.php'); ?>css/woocommerce-pip-print.css" rel=" stylesheet" type="text/css" media="print" />
	<link href="<?php echo woocommerce_pip_template('uri', 'template.php'); ?>css/woocommerce-pip.css" rel=" stylesheet" type="text/css" media="screen,print" />
        <script type="text/javascript" src="<?php echo WP_PLUGIN_URL  ?>/pip/js/jquery-1.3.2.min.js" ></script>  
        <script type="text/javascript" src="<?php echo WP_PLUGIN_URL  ?>/pip/js/jquery-barcode.js" ></script> 
</head>
<body <?php if ($client != true) echo woocommerce_pip_preview(); ?>>
