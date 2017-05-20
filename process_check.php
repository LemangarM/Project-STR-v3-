<?php

include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/Reviews.php';

// instantiate database and charts object
$database = new Database();
$db = $database->getConnection();

$reviews = new Reviews($db);

// update response comment
if ($_GET['id_comment'] && $_GET['reponse']) {
    $id_comment = $_GET['id_comment'];
    $reponse = $_GET['reponse'];
    if($reviews->updateReponse($id_comment, $reponse)){
        echo 'success update';
    }
}