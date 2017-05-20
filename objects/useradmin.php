<?php

class useradmin
{

    // database connection and table name
    //ajouter toutes les colonnes de la table
    private $conn;
    private $table_name = "users";
    // object properties
    public $login;
    public $last_name;
    public $first_date;
    public $profil;
    public $indicateur_first_login;
    public $password;
    public $date_last_login;
    public $date_last_maj;
    public $id;


    public function __construct($db)
    {
        $this->conn = $db;
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
			SET id = ?, login = ?, last_name = ?, first_name = ?, password = 'bcb15f821479b4d5772bd0ca866c00ad5f926e3580720659cc80d39c9d09802a', indicateur_first_login = '1', profil = 'Lecteur', date_last_maj = NOW()";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
       // $this->password = htmlspecialchars(strip_tags($this->password));
        //$this->indicateur_first_login = htmlspecialchars(strip_tags($this->indicateur_first_login));
       

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->login);
        $stmt->bindParam(3, $this->first_name);
        $stmt->bindParam(4, $this->last_name);
       // $stmt->bindParam(5, $this->password);
        //$stmt->bindParam(6, $this->indicateur_first_login);
        

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

   
    // read users
    public function readAll($from_record_num, $records_per_page)
    {

        // select query
        $query = "SELECT id,login, first_name, last_name, profil, date_last_login, date_last_maj
				FROM $this->table_name
				ORDER BY date_last_maj
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

        $query = "SELECT id,login, first_name, last_name, profil, password, date_last_login, date_last_maj
				FROM $this->table_name
				WHERE id = ?
				LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->login = $row['login'];
        $this->last_name = $row['last_name'];
        $this->first_name = $row['first_name'];
       // $this->password = $row['password'];
		

    }

    // update the product
    public function update()
    {

        $query = "UPDATE $this->table_name
			SET login = :login,
                        last_name = :last_name,
                        first_name = :first_name,
                        password = '96e79218965eb72c92a549dd5a330112',
			indicateur_first_login='1'
			    WHERE
                            id = :id";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        //$this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':first_name', $this->first_name);
        //$stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':id', $this->id);

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

        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

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
        $query = "DELETE FROM " . $this->table_name . " WHERE id IN ({$in_ids})";

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


}

?>
