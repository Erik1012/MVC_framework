<?php
	class test extends Controller {
		public  function __construct() 
			{
				parent::__construct();
			}
		public function other($arg = false) // test controllet other method; domain/test/other(/argument)
			{
				$all_from_table = test_model::model()->findAll(); // SELECT ALL
				$one_row = test_model::model()->findRow()->where(" id = 1 "); //SELECT row WHERE id = 1
				$rows_by_where = test_model::model()->where(" text1 = '1'")->findAll(); // SELECT ALL by WHERE
				/* INSERT EXAMPLE
				$test_model = new test_model();
				$test_model->text1 = "text1 for insert";
				$test_model->text2 = "text2 for insert";
				$test_model->save(); // INSERT
				*/
				
				/* UPDATE EXAMPLE
				$test_model = new test_model();
				$test_model->text1 = "text1 for update";
				$test_model->text2 = "text2 for update";
				$test_model->id = 1; // FOR UPDATE !!!
				$test_model->save(); // UPDATE
				*/
				
				//test_model::model()->where('id = 5')->delete(); // DELETE
				
				$this->view->render('test', array('message' => "Hello! this is test view for test=)", 'argument' => $arg, 'all_from_table' => $all_from_table, 'one_row' => $one_row, 'rows_by_where' => $rows_by_where));
			}
	}

?>

