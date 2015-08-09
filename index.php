<?php
	include_once 'config.php';
	$config = new Config();
	if(isset($_REQUEST['ajax']))
		{
			include_once('ajax/index.php');
			exit();
		}
	$router = new Router();
?>