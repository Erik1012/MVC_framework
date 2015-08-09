<?php
function autoload_folder($dir)
	{
		$directory = glob($dir."/*.php");
		foreach($directory as $name)
			{
				include_once $name;
			} 
	}
/* ======CREATED_BY_ERIK======= */
/* ===========2015============= */
?>
