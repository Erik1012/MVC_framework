<?php
	class View {
		public function __construct()
			{
				
			}
		public function render($view, $data = array(), $noInclude = false)
			{
				foreach($data as $key => $value)
					{
						$$key = $value;
					}
				if($noInclude == true) //if noInclude == true footer & header will not include
					{
						require 'views/'.$view.'.php';
					}
				else
					{
						require 'views/header.php';
						require 'views/'.$view.'.php';
						require 'views/footer.php';
					}
			}
	}
/* ======CREATED_BY_ERIK======= */
/* ===========2015============= */
?>
