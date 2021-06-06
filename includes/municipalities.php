<?php

include "db.php";
include "functions.php";

if(isset($_POST['province_code'])){
	$result = selectAllMunicipality($_POST['province_code']);
	$output = "";
	while($row = $result->fetch_assoc()){
		$output .= '<option value="' . $row['municipality_code'] . '">' . $row['municipality_name'] . '</option>';
	}
}

echo json_encode(array('success'=> true, 'content'=> $output)) ;

?>