<?php
// include database and object files
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/Alerts.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$alert = new Alertes($db);


// get search term
$search_term=isset($_GET['s']) ? $_GET['s'] : '';

$page_title = "You searched for \"{$search_term}\"";
//include_once "layout_header.php";

// query products
$stmt = $alert->search($search_term, $from_record_num, $records_per_page);
$num = $stmt->rowCount();

$page_url="search_alert.php?s={$search_term}&";
include_once "read_alertes_template.php";

include_once "layout_footer.php";
?>