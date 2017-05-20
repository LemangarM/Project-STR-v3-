<?php

class Alertes
{

    // database connection and table name
    private $conn;
    private $table_name = "alertes";
    // object properties
    public $id;
    public $name;
    public $start_date;
    public $end_date;
    public $store;
    public $app_id;
    public $app_name;
    public $criteria;
    public $value;
    public $nombre_occurence;
    public $mailing_list;
    public $nombre_occurence_happened;
    public $status;
    public $last_update_date;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // count records in date ranges
    public function countSearchByDateRange($date_from, $date_to)
    {

        // query to count records in date ranges
        $query = "SELECT COUNT(*) as total_rows
		    FROM alertes
		    WHERE
		    LastUpdateDate BETWEEN :date_from AND :date_to
			OR LastUpdateDate LIKE :date_from_for_query
			OR LastUpdateDate LIKE :date_to_for_query";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind date variables
        $stmt->bindParam(":date_from", $date_from);
        $stmt->bindParam(":date_to", $date_to);

        $date_from_for_query = "%{$date_from}%";
        $date_to_for_query = "%{$date_to}%";
        $stmt->bindParam(":date_from_for_query", $date_from_for_query);
        $stmt->bindParam(":date_to_for_query", $date_to_for_query);

        // execute query and get total rows
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    // read alertes by search term
    public function search($search_term, $from_record_num, $records_per_page)
    {

        // select query
        $query = "SELECT Id, Name, StartDate, EndDate, Store, appID, appName, Criteria, Value, NbOccurs, MailingList, NbOccursHappened, Status, LastUpdateDate
				FROM $this->table_name
				WHERE Name LIKE ? OR Store LIKE ? OR appID LIKE ? OR Criteria LIKE ? OR Value LIKE ? OR MailingList LIKE ? OR Status LIKE ?
				ORDER BY Name ASC
				LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $search_term);
        $stmt->bindParam(4, $search_term);
        $stmt->bindParam(5, $search_term);
        $stmt->bindParam(6, $search_term);
        $stmt->bindParam(7, $search_term);
        $stmt->bindParam(8, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(9, $records_per_page, PDO::PARAM_INT);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    public function countAll_BySearch($search_term)
    {

        // select query
        $query = "SELECT COUNT(*) as total_rows
				FROM  $this->table_name
				WHERE Name LIKE ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    // create alertes
    public function create()
    {

        // to get time-stamp for 'created' field
        $this->getTimestamp();

        //write query
        $query = "INSERT INTO $this->table_name
			SET Name = ?, StartDate = ?, EndDate = ?, Store = ?, appID = ?, appName = ?, Criteria = ?, Value = ?, NbOccurs = ?, MailingList = ?, NbOccursHappened = ?, Status = ?, LastUpdateDate = ?";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));
        $this->store = htmlspecialchars(strip_tags($this->store));
        $this->app_id = htmlspecialchars(strip_tags($this->app_id));
        $this->app_name = htmlspecialchars(strip_tags($this->app_name));
        $this->criteria = htmlspecialchars(strip_tags($this->criteria));
        $this->value = htmlspecialchars(strip_tags($this->value));
        $this->nombre_occurence = htmlspecialchars(strip_tags($this->nombre_occurence));
        $this->mailing_list = htmlspecialchars(strip_tags($this->mailing_list));
        $this->nombre_occurence_happened = htmlspecialchars(strip_tags($this->nombre_occurence_happened));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->last_update_date = htmlspecialchars(strip_tags($this->last_update_date));

        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->start_date);
        $stmt->bindParam(3, $this->end_date);
        $stmt->bindParam(4, $this->store);
        $stmt->bindParam(5, $this->app_id);
        $stmt->bindParam(6, $this->app_name);
        $stmt->bindParam(7, $this->criteria);
        $stmt->bindParam(8, $this->value);
        $stmt->bindParam(9, $this->nombre_occurence);
        $stmt->bindParam(10, $this->mailing_list);
        $stmt->bindParam(11, $this->nombre_occurence_happened);
        $stmt->bindParam(12, $this->status);
        $stmt->bindParam(13, $this->last_update_date);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // read products with field sorting
    public function readAll_WithSorting($from_record_num, $records_per_page, $field, $order)
    {

        $query = "SELECT Id, Name, StartDate, EndDate, Store, appID, appName, Criteria, Value, NbOccurs, MailingList, NbOccursHappened, Status, LastUpdateDate
					FROM alertes
					ORDER BY {$field} {$order}
					LIMIT :from_record_num, :records_per_page";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    // read products
    public function readAll($from_record_num, $records_per_page)
    {

        // select query
        $query = "SELECT Id, Name, StartDate, EndDate, Store, appID, appName, Criteria, Value, NbOccurs, MailingList, NbOccursHappened, Status, LastUpdateDate
				FROM $this->table_name
				ORDER BY LastUpdateDate DESC
				LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    // read products
    public function readAll_ByAppName($from_record_num, $records_per_page)
    {

        // select query
        $query = "SELECT Id, Name, StartDate, EndDate, Store, appID, appName, Criteria, Value, NbOccurs, MailingList, NbOccursHappened, Status, LastUpdateDate
				FROM  $this->table_name
				WHERE appName=?
				ORDER BY Name ASC
				LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $stmt->bindParam(1, $this->app_name);
        $stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    // read products
    public function countAll_ByAppName()
    {

        // select query
        $query = "SELECT COUNT(*) as total_rows
				FROM  $this->table_name
				WHERE appName=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->app_name);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    // used for paging product list with field sorting
    public function countAll_WithSorting($field, $order)
    {
        // for now countAll() is used
    }

    // used for paging products
    public function countAll()
    {
        $query = "SELECT COUNT(*) as total_rows FROM $this->table_name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    // used when filling up the update product form
    public function readOne()
    {

        $query = "SELECT Name, StartDate, EndDate, Store, appID, appName, Criteria, Value, NbOccurs, MailingList, NbOccurs, Status, LastUpdateDate
				FROM $this->table_name
				WHERE Id = ?
				LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['Name'];
        $this->start_date = $row['StartDate'];
        $this->end_date = $row['EndDate'];
        $this->store = $row['Store'];
        $this->app_id = $row['appID'];
        $this->app_name = $row['appName'];
        $this->criteria = $row['Criteria'];
        $this->value = $row['Value'];
        $this->nombre_occurence = $row['NbOccurs'];
        $this->mailing_list = $row['MailingList'];
        $this->status = $row['Status'];
        $this->last_update_date = $row['LastUpdateDate'];
    }

    // update the product
    public function update()
    {

        $query = "UPDATE $this->table_name
			SET Name = :Name,
                        StartDate = :StartDate,
                        EndDate = :EndDate,
                        Store = :Store,
                        appID = :appID,
                        appName = :appName,
                        Value = :Value,
                        MailingList = :MailingList,
                        NbOccurs = :NbOccurs,
                        Status = :Status,
                        LastUpdateDate = NOW()
			    WHERE
                            Id = :Id";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));
        $this->store = htmlspecialchars(strip_tags($this->store));
        $this->app_id = htmlspecialchars(strip_tags($this->app_id));
        $this->app_name = htmlspecialchars(strip_tags($this->app_name));
//        $this->criteria = htmlspecialchars(strip_tags($this->criteria));
        $this->value = htmlspecialchars(strip_tags($this->value));
        $this->mailing_list = htmlspecialchars(strip_tags($this->mailing_list));
        $this->nombre_occurence_happened = htmlspecialchars(strip_tags($this->nombre_occurence));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':Name', $this->name);
        $stmt->bindParam(':StartDate', $this->start_date);
        $stmt->bindParam(':EndDate', $this->end_date);
        $stmt->bindParam(':Store', $this->store);
        $stmt->bindParam(':appID', $this->app_id);
        $stmt->bindParam(':appName', $this->app_name);
//        $stmt->bindParam(':Criteria', $this->criteria);
        $stmt->bindParam(':Value', $this->value);
        $stmt->bindParam(':MailingList', $this->mailing_list);
        $stmt->bindParam(':NbOccurs', $this->nombre_occurence);
        $stmt->bindParam(':Status', $this->status);
        $stmt->bindParam(':Id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // delete the product
    public function delete()
    {

        $query = "DELETE FROM " . $this->table_name . " WHERE Id = ?";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($result = $stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // delete selected alertes
    public function deleteSelected($ids)
    {

        $in_ids = str_repeat('?,', count($ids) - 1) . '?';

        // query to delete multiple records
        $query = "DELETE FROM " . $this->table_name . " WHERE Id IN ({$in_ids})";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute($ids)) {
            return true;
        } else {
            return false;
        }
    }

    // used for the 'created' field when creating a product
    public function getTimestamp()
    {
        date_default_timezone_set('Europe/Paris');
        $this->last_update_date = date('Y-m-d H:i:s');
    }

    // read store
    public function readStore()
    {
        //select all data
        $query = "SELECT DISTINCT Store
			FROM " . $this->table_name . "
			";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // read Apps
    public function readApps()
    {
        //select all data
        $query = "SELECT DISTINCT appName
			FROM " . $this->table_name . "
			";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // read Criteria
    public function readCriteria()
    {
        //select all data
        $query = "SELECT Criteria
			FROM " . $this->table_name . "
			WHERE Id= ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }

    // return appName By ID
    public function getappNameByIb()
    {
        $query = "SELECT appName
                 FROM appstore
                 WHERE appID=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->app_id);
        $stmt->execute();
        $name = $stmt->fetch(PDO::FETCH_ASSOC);
        return $name;
    }

    public function getAppNameList()
    {
        $query = "
            SELECT appID, appName
            FROM apps_infos
            ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getappNameByIdAlert()
    {
        $query = "
        SELECT b.Id, a.appID, a.appName
        FROM apps_infos a
        INNER JOIN alertes b on a.appID=b.appID
        where b.Id= ?
          ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function getStatusList()
    {
        $query = "
            SELECT Id, Status
            FROM alertstatus
            ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getStatusByNameAlert()
    {
        $query = "
        SELECT a.Status, b.Status
        FROM alertstatus a
        INNER JOIN alertes b ON a.Status=b.Status
        WHERE b.Id= ?
          ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

}

?>
