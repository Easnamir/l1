<?php
// var_dump($_POST);

			$html = $_POST['table_rows'];
			header('Content-Type: application/xls');
			$file="Tally_data.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;
?>