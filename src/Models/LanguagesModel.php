<?php

namespace Src\Models;

class LanguagesModel{

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
                language 
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
                language
            WHERE 
                language_id = ? AND status = ?;
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

        // find template by language name 

        public function findByLanguageName($languageName)
        {
            $statement = "
                SELECT 
                    *
                FROM
                    language
                WHERE 
                    language_name = ? AND status = ?;
            ";
    
            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array($languageName,1));
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                return $result;
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }    
        }
      
  
    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO language 
                (language_name)
            VALUES
                (:language_name);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':language_name' => $input['language_name'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "

            UPDATE language

            SET 
                language_name = :language_name,
                 status = :status

            WHERE language_id = :language_id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
               
               ':language_name' => $input['language_name'],
               ':language_id' => (int) $id,               
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
            DELETE FROM language
            WHERE language_id = :language_id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('language_id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    



}


?>
