<?php

class Users
{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function updatePassword($password, $id)
    {
        $query = "UPDATE $this->table_name
			SET password = ?, indicateur_first_login = 0, date_last_maj = now() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $password);
        $stmt->bindParam(2, $id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function selectUserId($login, $password)
    {
        $query = "SELECT id FROM $this->table_name
			WHERE login= ? AND password=? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $login);
        $stmt->bindParam(2, $password);

        // execute the query
        $stmt->execute();
        return $stmt;
    }

    public function selectFirstLogin($login, $password)
    {
        $query = "SELECT indicateur_first_login, CONCAT(first_name,' ',last_name) as name, profil FROM $this->table_name
			WHERE login= ? AND password=? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $login);
        $stmt->bindParam(2, $password);

        // execute the query
        $stmt->execute();
        return $stmt;
    }

    public function selectLoginPassword()
    {
        $query = "SELECT login, password
                  FROM $this->table_name
			WHERE 1";
        $stmt = $this->conn->prepare($query);

        // execute the query
        $stmt->execute();
        return $stmt;
    }

}
