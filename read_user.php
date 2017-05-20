<?php
// include database and object files
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/useradmin.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$user = new useradmin($db);

// header settings
$page_title = "Liste des utilisateurs";
//include_once "layout_header.php";

// query products
$stmt = $user->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();

$page_url="read_user.php?";
include_once "read_user_template.php";

include_once "layout_footer_user.php";
?>
