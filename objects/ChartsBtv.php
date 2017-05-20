<?php

Class ChartsBtv {

    //Database attribut
    private $conn;
    //Attributs
    private $appID;
    private $appName;
    private $DateMeasure;
    private $Total_Average_Rating;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getPalmares($start_date, $end_date, $id) {
        $query = "SELECT a.appID, a.appName, c.datefield as DateMeasure, IFNULL(a.Total_Average_Rating,'null') As Total_Average_Rating
                  FROM apps_concurrence a
                  RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) AND a.appID = ?
                  WHERE (c.datefield Between ? AND ?) order by c.datefield";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();
        return $stmt;
    }
    
    public function getPalmaresBtv($start_date, $end_date, $id) {
        $query = "SELECT a.appID, c.datefield as DateMeasure, IFNULL(a.Total_Average_Rating,'null') As Total_Average_Rating
                  FROM apps_ratings a
                  RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) AND a.appID = ?
                  WHERE (c.datefield Between ? AND ?) order by c.datefield";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();
        return $stmt;
    }
    
    //VOD
    public function getTotalVOD($id) {
        $query = "SELECT DATE_FORMAT(a.DateMeasure, '%M %Y') as DateMeasure, SUM(a.Unites) as Total
                  FROM vu_rpvr_vod a
                  where a.DateMeasure = (select max(DateMeasure) from vu_rpvr_vod) 
                  AND a.appID = ? AND a.Type = 'VOD à la carte'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    
    public function getVOD($id) {
        $query = "SELECT a.DateMeasure, a.Page, a.Unites
                  FROM vu_rpvr_vod a
                  where a.DateMeasure = (select max(DateMeasure) from vu_rpvr_vod) 
                  AND a.appID = ? AND a.Type = 'VOD à la carte'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    
    //RPVR
    public function getTotalRPVR($id) {
        $query = "SELECT DATE_FORMAT(a.DateMeasure, '%M %Y') as DateMeasure, SUM(a.Unites) as Total
                  FROM vu_rpvr_vod a
                  where a.DateMeasure = (select max(DateMeasure) from vu_rpvr_vod) 
                  AND a.appID = ? AND a.Type = 'RPVR'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    
    public function getRPVR($id) {
        $query = "SELECT a.DateMeasure, a.Page, a.Unites
                  FROM vu_rpvr_vod a
                  where a.DateMeasure = (select max(DateMeasure) from vu_rpvr_vod) 
                  AND a.appID = ? AND a.Type = 'RPVR'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    
    //OS
    public function getTotalUnitesOS($id) {
        $query = "SELECT DATE_FORMAT(a.DateMeasure, '%M %Y') as DateMeasure, SUM(a.Unites) as Total
                  FROM vu_os_version a
                  where a.DateMeasure = (select max(DateMeasure) from vu_os_version) 
                  AND a.appID = ? AND a.Type = 'Os'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    
    public function getUnitesOS($id) {
        $query = "SELECT a.DateMeasure, a.OSVersion, a.Unites
                  FROM vu_os_version a
                  where a.DateMeasure = (select max(DateMeasure) from vu_os_version) 
                  AND a.appID = ? AND a.Type = 'Os'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    
    //Version
    public function getTotalUnitesVersion($id) {
        $query = "SELECT DATE_FORMAT(a.DateMeasure, '%M %Y') as DateMeasure, SUM(a.Unites) as Total
                  FROM vu_os_version a
                  where a.DateMeasure = (select max(DateMeasure) from vu_os_version) 
                  AND a.appID = ? AND a.Type = 'Version'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    
    public function getUnitesVersion($id) {
        $query = "SELECT a.DateMeasure, a.OSVersion, a.Unites
                  FROM vu_os_version a
                  where a.DateMeasure = (select max(DateMeasure) from vu_os_version) 
                  AND a.appID = ? AND a.Type = 'Version'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    
    public function getTotalCommentNull() {
        $query = "select count(*) as total from appstore_reviews 
                  where appID in('0004BTVSM','739824309') and (review = '' or null) and date_epoch >= (select max(date_epoch) from appstore_reviews) - INTERVAL 1 MONTH 
                  ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function getTotalCommentNotNull() {
        $query = "select count(*) as total from appstore_reviews 
                  where appID in('0004BTVSM','739824309') and review <> '' AND date_epoch >= (select max(date_epoch) from appstore_reviews) - INTERVAL 1 MONTH
                  ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function getRepartitionCommentNull() {
        $query = "select CONCAT(stars,' ', 'étoiles') as stars, count(*) as repartition from appstore_reviews 
                  where appID in('0004BTVSM','739824309') and (review = '' or null) AND date_epoch >= (select max(date_epoch) from appstore_reviews) - INTERVAL 1 MONTH
                  Group by stars";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function getRepartitionCommentNotNull() {
        $query = "select CONCAT(stars,' ', 'étoiles') as stars, count(*) as repartition from appstore_reviews 
                  where appID in('0004BTVSM','739824309') and review <> '' AND date_epoch >= (select max(date_epoch) from appstore_reviews) - INTERVAL 1 MONTH
                  Group by stars";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function getTotalANRs() {
        $query = "SELECT AppVersionName, COUNT(*) as Total
                  FROM anrs a
                  WHERE a.ANRReportDateAndTime >= (select max(ANRReportDateAndTime) from anrs) - INTERVAL 1 MONTH";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function getRepartitionANRs() {
        $query = "SELECT AppVersionName as version, COUNT(*) as repartition
                  FROM anrs a
                  WHERE a.ANRReportDateAndTime >= (select max(ANRReportDateAndTime) from anrs) - INTERVAL 1 MONTH
                  GROUP BY AppVersionName";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function getTotalCraches() {
        $query = "SELECT AppVersionName, COUNT(*) as Total
                  FROM craches a
                  WHERE a.CrashReportDateAndTime >= (select max(CrashReportDateAndTime) from craches) - INTERVAL 1 MONTH";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function getRepartitionCraches() {
        $query = "SELECT AppVersionName as version, COUNT(*) as repartition
                  FROM craches a
                  WHERE a.CrashReportDateAndTime >= (select max(CrashReportDateAndTime) from craches) - INTERVAL 1 MONTH
                  GROUP BY AppVersionName";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
}
