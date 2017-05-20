<?php
// include database and object files
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/Alerts.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$alert = new Alertes($db);

// header settings
$page_title = "Liste des alertes";
//include_once "layout_header.php";

// query products
$stmt = $alert->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();

$page_url="read_alertes.php?";
include_once "read_alertes_template.php";

include_once "layout_footer.php";
?>
