<?php
// check if value was posted
if($_POST){

	// include database and object file
	include_once 'config/database.php';
	include_once 'objects/useradmin.php';

	// get database connection
	$database = new Database();
	$db = $database->getConnection();

	// prepare product object
	$useradmin = new useradmin($db);
	
	// set product id to be deleted
	$useradmin->id = $_POST['object_id'];
	
	// delete the product
	if($useradmin->delete()){
		echo "Object was deleted.";
	}
	
	// if unable to delete the product
	else{
		echo "Unable to delete object.";	
	}
}
?>