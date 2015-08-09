<?php
	class Error extends Controller {
		public function __construct() 
			{
				parent::__construct();
				$this->view->render('error', array('error_message' => "Error 404"));
			}
	}
?>
