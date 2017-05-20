<?php
// check if value was posted
if($_POST){
	
	// include database and object file
	include_once 'config/database.php';
	include_once 'objects/Alerts.php';

	// get database connection
	$database = new Database();
	$db = $database->getConnection();

	// prepare product object
	$alert = new Alertes($db);
	
	// delete the product
	if($alert->deleteSelected($_POST['del_checkboxes'])){
		// records were deleted
	}
	
	// if unable to delete the product
	else{
		echo "Unable to delete records.";
	}
}
