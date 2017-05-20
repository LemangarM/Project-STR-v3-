<?php

class Reviews
{

    // database connection and table name
    private $conn;
    private $table_name = "appstore_reviews";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /*
     * Récupère tous les commentaires
     * @param $appIdAllStore, $startdate, $enddate
     */

    public function readAll($appIdAllStore, $startdate, $enddate, $keywords_url)
    {

        // select query
        $query = "SELECT  a.id, a.Store, a.appID, a.title, a.review, a.stars, a.user_id, a.date_epoch, a.url, a.comment, a.device, a.version, a.versionOS, a.reponse, CONCAT(p.Marque, '+ [', Modele,']') as Device, Format(p.Volume,0,'de_DE') as Volume
            FROM " . $this->table_name . " a
                  LEFT JOIN apps_infos b ON a.appID = b.appID
                  LEFT JOIN parc_devices p ON p.Device = a.device
                  WHERE b.appIdAllStore = :appIdAllStore";
        if ($keywords_url !== "") {
            $query.= " AND LOWER(review) REGEXP :keywords_url";
        }
        $query.= " AND (DATE(a.date_epoch) BETWEEN :startdate AND :enddate)
                   AND a.review IS NOT NULL AND a.review != ''
                   ORDER BY a.date_epoch DESC ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $stmt->bindParam(":appIdAllStore", $appIdAllStore);
        if ($keywords_url !== "") {
            $stmt->bindParam(":keywords_url", $keywords_url);
        }
        $stmt->bindParam("startdate", $startdate);
        $stmt->bindParam(":enddate", $enddate);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    /*
     * Mise à jour de la remarque commentaire
     * @param $id, $data
     */

    public function update($id, $data)
    {

        $query = "UPDATE $this->table_name
			SET comment = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $data);
        $stmt->bindParam(2, $id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Supprime la remarque commentaire séléctionné
     * @param $id
     */

    public function delete($id)
    {

        $query = "UPDATE $this->table_name
			SET comment ='' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Calcul le nombre de commentaires Google play récupérés
     * @param $appIdAllStore, $startdate, $enddate
     */

    public function countReviewsAndroid($appIdAllStore, $startdate, $enddate, $keywords_url)
    {
        // select query
        $query = "SELECT  count(a.Store) as count
        FROM " . $this->table_name . " a
                  LEFT JOIN apps_infos b ON a.appID = b.appID
                  WHERE b.appIdAllStore = :appIdAllStore
                  AND a.appID LIKE '0%'";
        if ($keywords_url !== "") {

            $query.= " AND LOWER(review) REGEXP :keywords_url";
        }
        $query.= " AND (DATE(a.date_epoch) BETWEEN :startdate AND :enddate)
                  AND a.review IS NOT NULL AND a.review != ''
                  ORDER BY a.date_epoch DESC ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $stmt->bindParam(":appIdAllStore", $appIdAllStore);
        if ($keywords_url !== "") {
            $stmt->bindParam(":keywords_url", $keywords_url);
        }
        $stmt->bindParam("startdate", $startdate);
        $stmt->bindParam(":enddate", $enddate);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    /*
     * Calcul le nombre de commentaires App store récupérés
     * @param $appIdAllStore, $startdate, $enddate
     */

    public function countReviewsIos($appIdAllStore, $startdate, $enddate, $keywords_url)
    {
        // select query
        $query = "SELECT  count(a.Store) as count
      FROM " . $this->table_name . " a
                  LEFT JOIN apps_infos b ON a.appID = b.appID
                  WHERE b.appIdAllStore = :appIdAllStore
                  AND a.appID NOT LIKE '0%'";
        if ($keywords_url !== "") {
            $query.= " AND LOWER(review) REGEXP :keywords_url";
        }
        $query.= " AND (DATE(a.date_epoch) BETWEEN :startdate AND :enddate)
                  AND a.review IS NOT NULL AND a.review != ''
                  ORDER BY a.date_epoch DESC ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $stmt->bindParam(":appIdAllStore", $appIdAllStore);
        if ($keywords_url !== "") {
            $stmt->bindParam(":keywords_url", $keywords_url);
        }
        $stmt->bindParam("startdate", $startdate);
        $stmt->bindParam(":enddate", $enddate);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    /*
     * Récupère la liste des mots clés 
     */

    public function readAllKeywords()
    {
        $query = "SELECT label, keywords
                    FROM keywords
                    LIMIT 8";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    /*
     * Compte le nombre d'occurence pour chaque liste de variantes 
     * @param $appIdAllStore, $keywords
     */

    public function countOccurs($appIdAllStore, $startdate, $enddate, $keywords)
    {
        $query = "SELECT COUNT(*) AS Nbtrouve
                    FROM appstore_reviews a
                    LEFT JOIN apps_infos b ON a.appID = b.appID
                    WHERE b.appIdAllStore = ?
                    AND (DATE(a.date_epoch) BETWEEN ? AND ?)
                    AND LOWER(review) REGEXP ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $stmt->bindParam(1, $appIdAllStore);
        $stmt->bindParam(2, $startdate);
        $stmt->bindParam(3, $enddate);
        $stmt->bindParam(4, $keywords);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    /*
     * Mise à jour de la reponse
     * @param $id_reponse, $reponse
     */

    public function updateReponse($id_reponse, $reponse)
    {
        $query = "UPDATE $this->table_name
			SET reponse = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $reponse);
        $stmt->bindParam(2, $id_reponse);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

}
