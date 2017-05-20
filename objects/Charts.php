<?php

define("ID_GLOBAL", "0999BBDUO");
$date = strtotime(date('Y-m-d'));
/*
  Author LEMANGAR
 */

class Charts {

    private $conn;
    public $text_graph;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAppNameList() {
        $query = "
            SELECT DISTINCT appIdAllStore, appName
            FROM apps_infos
            ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function appName($id_global = ID_GLOBAL) {
        $query = "
            SELECT DISTINCT appIdAllStore, appName
            FROM apps_infos
            WHERE appIdAllStore = :id_global
            ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_global", $id_global);
        $stmt->execute();

        return $stmt;
    }

    /*
     * Table infos générales
     */

    public function InfosAndroid($id_global = ID_GLOBAL) {
        $query = "
            SELECT a.appID, b.appURL, appVersion, appMinimumOsVersion, appCurrentStars, Format(Unites_total,'null','de_DE') as Unites_total, Format(c.Unites_cumul,'null','de_DE') as Unites_cumul, currentVersionReleaseDate
            FROM appstore a
            LEFT JOIN apps_infos b ON a.appID=b.appID
            LEFT JOIN sales_cumul c ON a.appID=c.appID
            WHERE b.appIdAllStore = :id_global
                AND a.appID LIKE '%00%'
            ORDER BY c.DateMeasure DESC LIMIT 1
               ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_global", $id_global);
        $stmt->execute();

        return $stmt;
    }

    public function InfosIos($id_global = ID_GLOBAL) {
        $query = "
            SELECT a.appID, b.appURL, appVersion, appMinimumOsVersion, appCurrentStars, Format(Unites_total,'null','de_DE') as Unites_total, Format(c.Unites_cumul,'null','de_DE') as Unites_cumul, currentVersionReleaseDate
            FROM appstore a
            LEFT JOIN apps_infos b ON a.appID=b.appID
            LEFT JOIN sales_cumul c ON a.appID=c.appID
            WHERE b.appIdAllStore = :id_global
                AND a.appID NOT LIKE '%00%'
            ORDER BY c.DateMeasure DESC LIMIT 1
               ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_global", $id_global);
        $stmt->execute();

        return $stmt;
    }

    /*
     * Retourne les visiteurs Google Play
     */

    public function visitorAndroid($id_global = ID_GLOBAL) {
        $query = "SELECT a.appID, Format(d.Unites,'null','de_DE') as Unites, count(d.Unites) as 'count'
            FROM appstore a
            LEFT JOIN apps_infos b ON a.appID=b.appID
            LEFT JOIN appstore_uvisitor d on a.appID =d.appID
            WHERE b.appIdAllStore = :id_global
                AND a.appID LIKE '%00%'
                AND d.DateMeasure= (SELECT Max(DateMeasure) FROM appstore_uvisitor)
            ORDER BY d.DateMeasure DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_global", $id_global);
        $stmt->execute();

        return $stmt;
    }

    /*
     * Retourne les visiteurs App Store
     */

    public function visitorIos($id_global = ID_GLOBAL) {
        $query = "SELECT a.appID, Format(d.Unites,'null','de_DE') as Unites, count(d.Unites) as 'count'
            FROM appstore a
            LEFT JOIN apps_infos b ON a.appID=b.appID
            LEFT JOIN appstore_uvisitor d on a.appID =d.appID
            WHERE b.appIdAllStore = :id_global
                AND a.appID not LIKE '%00%'
                AND d.DateMeasure= (SELECT Max(DateMeasure) FROM appstore_uvisitor)
            ORDER BY d.DateMeasure DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_global", $id_global);
        $stmt->execute();

        return $stmt;
    }

    /*
     * Install & uninstall & upgrade line chart
     */

    public function getSalesAndroid($start_date, $end_date, $id_app) {
        $query = "
            SELECT c.datefield, a.appID, date_format(DateMeasure,'%d/%m/%Y') as date, DateMeasure, IFNULL(Unites,'null') AS Unites, IFNULL(Daily_uninstall,'null') AS Daily_uninstall, IFNULL(Daily_upgrade,'null') AS Daily_upgrade
            FROM appstore_sales a
            RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
            AND a.appID = ?
            WHERE (c.datefield Between ? AND ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        return $stmt;
    }

    public function getSalesIos($start_date, $end_date, $id_app) {
        $query = "
            SELECT c.datefield, a.appID, DateMeasure, IFNULL(Unites,'null') AS Unites, IFNULL(Daily_uninstall,'null') AS Daily_uninstall, IFNULL(Daily_upgrade,'null') AS Daily_upgrade
            FROM appstore_sales a
            RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
            AND a.appID = ?
            WHERE (c.datefield Between ? AND ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        return $stmt;
    }

    /*
     * Visitors bar chart
     */

    public function getVisitorsAndroid($id_global = ID_GLOBAL) {
        $query = "
           SELECT a.appID,Unites, date_format(DateMeasure, '%b %Y') as date,DateMeasure
           FROM appstore_uvisitor a
           LEFT JOIN apps_infos b ON a.appID=b.appID
           WHERE b.appIdAllStore = :id_global
               AND a.appID LIKE '%00%'
           ORDER BY DateMeasure DESC LIMIT 7
           ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_global", $id_global);
        $stmt->execute();

        return $stmt;
    }

    public function getVisitorsIos($id_global = ID_GLOBAL) {
        $query = "
           SELECT a.appID,Unites, DateMeasure
           FROM appstore_uvisitor a
           LEFT JOIN apps_infos b ON a.appID=b.appID
           WHERE b.appIdAllStore = :id_global
               AND a.appID NOT LIKE '%00%'
           ORDER BY DateMeasure DESC LIMIT 7
           ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_global", $id_global);
        $stmt->execute();

        return $stmt;
    }

    /*
     * Notes line chart
     */

    public function getNotesAndroid($start_date, $end_date, $id_app) {
        $query = "
            SELECT a.appID, DateMeasure, IFNULL(Daily_Average_Rating,'null') AS Daily_Average_Rating, IFNULL(Total_Average_Rating,'null') AS Total_Average_Rating
            FROM apps_ratings a
            RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
            AND a.appID = ?
            WHERE (c.datefield Between ? AND ?)
            AND c.datefield >= now() - INTERVAL 18 MONTH
            ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        return $stmt;
    }

    public function getNotesIos($start_date, $end_date, $id_app) {
        $query = "
            SELECT a.appID, DateMeasure, IFNULL(Total_Average_Rating,'null') AS Total_Average_Rating
            FROM apps_ratings a
            RIGHT JOIN calendar c on c.datefield=DATE(a.DateMeasure) 
            AND a.appID = ?
            WHERE (c.datefield Between ? AND ?)
            AND c.datefield >= now() - INTERVAL 18 MONTH

            ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);
        $stmt->execute();

        return $stmt;
    }

    // get dates between two date
    public function getDatesBetweenTwoDates($start_date, $end_date) {
        $query = "select  DATE_FORMAT(selected_date,'%d %b') as date from 
              (select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date from
              (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
              (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
              (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
              (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
              (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
              where selected_date >= ? 
              AND selected_date <= ? ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $start_date);
        $stmt->bindParam(2, $end_date);
        $stmt->execute();
        return $stmt;
    }

    //get appID Appstore
    public function getappIDIos($id_global = ID_GLOBAL) {
        $query = "
            SELECT a.appID
            FROM apps_infos a
            WHERE a.appIdAllStore = ?
            AND a.appID not like '%00%'
            ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $id_global);
        $stmt->execute();

        return $stmt;
    }

    //get appID Google Play
    public function getappIDAndroid($id_global = ID_GLOBAL) {
        $query = "
            SELECT a.appID
            FROM apps_infos a
            WHERE a.appIdAllStore = ?
            AND a.appID like '%00%'
            ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_global);
        $stmt->execute();

        return $stmt;
    }

    //update comment graph
    public function updateTextGraph($text, $id_global = ID_GLOBAL) {

        $query = "REPLACE INTO comment_graph (comment,appIdAllStore)
			VALUES(?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $text);
        $stmt->bindParam(2, $id_global);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // get text graph 
    public function getTextGraph($id_global = ID_GLOBAL) {
        $query = "SELECT comment 
                  FROM comment_graph WHERE appIdAllStore = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_global);

        // execute the query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->text_graph = $row['comment'];
    }

    public function getMaxDateRating($id = '0003BBDUO') {
        $query = "select max(DateMeasure) as date from apps_ratings a
                  LEFT JOIN apps_infos b ON a.appID=b.appID
                  WHERE a.appID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        $stmt->execute();

        return $stmt;
    }

    public function getMaxDateSales($id_global = ID_GLOBAL) {
        $query = "select max(DateMeasure) as date from appstore_sales a 
                  LEFT JOIN apps_infos b ON a.appID=b.appID
                  WHERE appIdAllStore = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_global);

        $stmt->execute();

        return $stmt;
    }

    public function getNbrDeNotes($start_date, $end_date, $id_app) {
        $query = "SELECT DATE(date_epoch) as date_epoch, COUNT(date_epoch) AS nbrdenote, c.datefield AS date
                  FROM appstore_reviews a
                  right JOIN calendar c ON c.datefield= DATE(a.date_epoch) AND appID = ?
                  WHERE (c.datefield Between ? AND ?) 
                  GROUP BY c.datefield
                  ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_app);
        $stmt->bindParam(2, $start_date);
        $stmt->bindParam(3, $end_date);

        $stmt->execute();

        return $stmt;
    }

}
