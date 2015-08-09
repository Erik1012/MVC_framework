<?php
	define('URL', 'http://hello.kmdfksz.net/');
	
	class Config
		{
			public function __construct() 
				{
					require_once 'autoload.php';
					autoload_folder('engine');
					autoload_folder('models');
				}
			public static $db = array(
            "host"=>"db15.freehost.com.ua",
            "db"=>"kmdfksz_hello",
            "login"=>"kmdfksz_hello",
				"password"=>"orXs9k2JW",
         );
			public static $title = "I`m BATMAN";
		}
/* ======CREATED_BY_ERIK======= */
/* ===========2015============= */
?>
