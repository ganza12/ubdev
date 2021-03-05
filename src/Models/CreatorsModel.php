<?php

namespace Src\Models;

class CreatorsModel{

    private $db = null;
    
    public function __construct($connection){

        $this->db = $connection;

    }


    public function findAll()
    {
      $statement = "
            SELECT 
                *
            FROM
                creators 
            WHERE 
                status = 1;
      ";

      try {
          $statement = $this->db->query($statement);
          $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }
    public function find($id)
    {
        $statement = "
            SELECT 
                *
            FROM
                creators
            WHERE 
                creator_id = ? AND status = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id,1));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
    public function findByEmail($email)
    {
        $statement = "
            SELECT 
                *
            FROM
                creators
            WHERE (email = ? ) AND status = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($email,1));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }


    public function findByEmailUsername($email = null, $username = null)
    {
        $statement = "
            SELECT 
                *
            FROM
                creators
            WHERE (email = ? || username = ? )AND status = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($email,$username,1));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO creators 
                (names, username, password, email)
            VALUES
                (:names, :username, :password, :email);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':names' => $input['names'],
                ':username' => $input['username'],
                ':password' => $input['password'],
                ':email' => $input['email']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "

            UPDATE creators

            SET 
                names = :names,
                username = :username,
                email = :email

            WHERE creator_id = :creator_id AND status = :status;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
               
               ':names' => $input['names'],
               ':username' => $input['username'],
               ':email' => $input['email'],
               ':creator_id' => (int) $id,               
                ':status' => 1

            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
   public function updatePassword($id, Array $input)
    {
        $statement = "
            UPDATE 
                creators
            SET 
                password = :password
            WHERE 
                creator_id = :creator_id AND status = :status;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':password' => $input['new_password'],
                ':creator_id' => $id,
                'status' => 1
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
    public function delete($id)
    {
        $statement = "
            DELETE FROM creators
            WHERE creator_id = :creator_id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('creator_id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    



}


?>
