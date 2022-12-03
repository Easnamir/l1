<?php 

$data = ($_REQUEST['data']);

$data_array = json_decode($data, true);

print_r($data_array);



?>