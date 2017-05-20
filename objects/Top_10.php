<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class top_10 {

    //Attributes
    private $appID;
    private $DateMeasure;
    private $Chaine;
    private $Unites;
    private $Clics;
    
    // database connection and table name
    private $conn;
    private $table_name = "top_10";

    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function findTopTenAndroid() {
        $query = "SELECT t.Chaine, t.Unites, t.Clics, DATE_FORMAT(t.DateMeasure, '%M %Y') as DateMeasure, t.Percentage
                     FROM $this->table_name t
                     WHERE t.DateMeasure = (select max(DateMeasure) from top_10)
                     AND t.OS = 'Android'
                     ORDER BY t.Unites Desc
                     LIMIT 10";
        $stmt = $this->conn->prepare($query);
        
        $stmt->execute();
        return $stmt;
    }
    
    public function findTopTenIos() {
        $query = "SELECT t.Chaine, t.Unites, t.Clics, DATE_FORMAT(t.DateMeasure, '%M %Y') as DateMeasure, t.Percentage
                     FROM $this->table_name t
                     WHERE t.DateMeasure = (select max(DateMeasure) from top_10)
                     AND t.OS = 'iOS'
                     ORDER BY t.Unites Desc
                     LIMIT 10";
        $stmt = $this->conn->prepare($query);
        
        $stmt->execute();
        return $stmt;
    }

}
