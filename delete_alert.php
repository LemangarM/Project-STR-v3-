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
	
	// set product id to be deleted
	$alert->id = $_POST['object_id'];
	
	// delete the product
	if($alert->delete()){
		echo "Object was deleted.";
	}
	
	// if unable to delete the product
	else{
		echo "Unable to delete object.";	
	}
}
?>