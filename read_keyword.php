<?php
// include database and object files
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/keyw.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$keyw = new keyw($db);

// header settings
$page_title = "Liste des mots clés";
//include_once "layout_header.php";

// query products
$stmt = $keyw->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();

$page_url="read_user.php?";
include_once "read_keyword_template.php";

include_once "layout_footer_keyword.php";
