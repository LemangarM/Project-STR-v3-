<?php
// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set('Europe/Paris');

// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// set number of records per page
$records_per_page = 10;

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
