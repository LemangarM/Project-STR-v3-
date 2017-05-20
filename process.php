<?php

include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/Reviews.php';

// instantiate database and charts object
$database = new Database();
$db = $database->getConnection();

$reviews = new Reviews($db);

// update comment
if ($_GET['id'] && $_GET['data']) {
    $id = $_GET['id'];
    $data = $_GET['data'];
    if($reviews->update($id, $data)){
        echo 'success update';
    }
}

// delete comment
if ($_GET['id'] && !$_GET['data']) {
    $id = $_GET['id'];
    if($reviews->delete($id)){
        echo 'success delete';
    }
}








