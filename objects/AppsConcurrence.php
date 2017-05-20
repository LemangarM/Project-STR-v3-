<?php

/*
 * Added By Med
 * 02/11/2016
 */

class appsConcurrence {

    //Attributes
    private $appID;
    private $appName;
    private $DateMeasure;
    private $Total_Average_Rating;
    
    
    // database connection and table name
    private $conn;
    private $table_name = "apps_concurrence";

    public function __construct($db) {
        $this->conn = $db;
    }

    // App Sore
    public function getNotesBouyguesIos($start_date, $end_date, $id_app) {
        $query = "SELECT c.datefield, a.appID, a.appName, a.DateMeasure, IFNULL(a.Total_Average_Rating,0) AS Total_Average_Rating
                   FROM $this->table_name a
                   RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
                   AND a.appID = ?
                   WHERE (c.datefield Between ? AND ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        $stmt->execute();
        return $stmt;
    }
    
    public function getNotesOrangeIos($start_date, $end_date, $id_app) {
        $query = "SELECT c.datefield, a.appID, a.appName, a.DateMeasure, IFNULL(a.Total_Average_Rating,0) AS Total_Average_Rating
                   FROM $this->table_name a
                   RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
                   AND a.appID = ?
                   WHERE (c.datefield Between ? AND ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        $stmt->execute();
        return $stmt;
    }
    
    public function getNotesSfrIos($start_date, $end_date, $id_app) {
        $query = "SELECT c.datefield, a.appID, a.appName, a.DateMeasure, IFNULL(a.Total_Average_Rating,0) AS Total_Average_Rating
                   FROM $this->table_name a
                   RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
                   AND a.appID = ?
                   WHERE (c.datefield Between ? AND ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        $stmt->execute();
        return $stmt;
    }
    
    // Google Play
    public function getNotesBouyguesAndroid($start_date, $end_date, $id_app) {
        $query = "SELECT c.datefield, a.appID, a.appName, a.DateMeasure, IFNULL(a.Total_Average_Rating,0) AS Total_Average_Rating
                   FROM $this->table_name a
                   RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
                   AND a.appID = ?
                   WHERE (c.datefield Between ? AND ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        $stmt->execute();
        return $stmt;
    }
    
    public function getNotesOrangeAndroid($start_date, $end_date, $id_app) {
        $query = "SELECT c.datefield, a.appID, a.appName, a.DateMeasure, IFNULL(a.Total_Average_Rating,0) AS Total_Average_Rating
                   FROM $this->table_name a
                   RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
                   AND a.appID = ?
                   WHERE (c.datefield Between ? AND ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        $stmt->execute();
        return $stmt;
    }
    
    public function getNotesSfrAndroid($start_date, $end_date, $id_app) {
        $query = "SELECT c.datefield, a.appID, a.appName, a.DateMeasure, IFNULL(a.Total_Average_Rating,0) AS Total_Average_Rating
                   FROM $this->table_name a
                   RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
                   AND a.appID = ?
                   WHERE (c.datefield Between ? AND ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        $stmt->execute();
        return $stmt;
    }
}
