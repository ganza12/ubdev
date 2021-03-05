<?php

namespace Src\Models;

class CategoryModel{

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
                category 
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
    
    // find template by template Id 

    public function find($id)
    {
        $statement = "
            SELECT 
                *
            FROM
                category
            WHERE 
                category_id = ? AND status = ?;
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

        // find by category_name 

        public function findBycategoryame($categoryame)
        {
            $statement = "
                SELECT 
                    *
                FROM
                    category                WHERE 
                    category_name = ? AND status = ?;
            ";
    
            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array($categoryame,1));
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                return $result;
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }    
        }
      
  
    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO category
                (category_name)
            VALUES
                (:category_name);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':category_name' => $input['category_name'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "

            UPDATE category
            SET 
                category_name = :category_name,
                 status = :status

            WHERE category_id = :category_id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
               
               ':category_name' => $input['category_name'],
               ':category_id' => (int) $id,               
                ':status' => 1

            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM category            WHERE category_id = :category_id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('category_id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    



}


?>
