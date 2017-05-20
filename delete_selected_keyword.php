<?php
// check if value was posted
if($_POST){

	// include database and object file
	include_once 'config/database.php';
	include_once 'objects/keyw.php';

	// get database connection
	$database = new Database();
	$db = $database->getConnection();

	// prepare product object
	$keyw = new keyw($db);
	
	// set product id to be deleted
	//$keyw->id = $_POST['object_id'];
	
	// delete the product
	if($keyw->deleteSelected($_POST['del_checkboxes'])){
		//echo "Object was deleted.";
	}
	
	// if unable to delete the product
	else{
		echo "Unable to delete object.";	
	}
}