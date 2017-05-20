<?php
// include database and object files
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/Alerts.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$alert = new Alertes($db);

// given field and order
$field = isset($_GET['field']) ? $_GET['field'] : "";
$order = isset($_GET['order']) ? $_GET['order'] : "";

// page header
$page_title="Liste des alertes";
//include_once "layout_header.php";

$stmt = $alert->readAll_WithSorting($from_record_num, $records_per_page, $field, $order);

//this is how to get number of rows returned
$num = $stmt->rowCount();

// to identify page for paging
$page_url="read_alertes_sorted_by_fields.php?field={$field}&order={$order}&";

// tell the template it is field sort
$field_sort=true;

// include the read template
include_once "read_alertes_template.php";

// page footer
include_once "layout_footer.php";
?>
