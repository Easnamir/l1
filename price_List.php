<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();



	extract($_POST);
	$html=$table_data;
			
			header('Content-Type: application/xls');
			$file="Price List.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;

?>