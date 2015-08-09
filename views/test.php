<?php
	echo $message.'<br/>';
	echo $argument;
	echo '<br/>all from table';
	echo '<pre>';
		print_r($all_from_table);
	echo '</pre>';
	foreach($all_from_table as $item)
		{
			echo 'text2 = '.$item->text2.'<br/>';
		}
	echo '<br/><br/>';
	echo 'one row';
	echo '<pre>';
		print_r($one_row);
	echo '</pre>';
	echo 'text1 = '.$one_row->text1;
	echo '<br/><br/>';
	
	
	echo 'rows_by_where';
	echo '<pre>';
		print_r($rows_by_where);
	echo '</pre>';
	foreach($rows_by_where as $item)
		{
			echo 'text2 = '.$item->text2.'<br/>';
		}
	echo '<br/><br/>';
/* ======CREATED_BY_ERIK======= */
/* ===========2015============= */
?>
<input type="button" onclick="ajax_send_example();" value="ajax test" />