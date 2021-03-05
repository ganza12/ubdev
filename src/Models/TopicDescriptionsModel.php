<?php

namespace Src\Models;

class TopicDescriptionsModel{

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
                topic_descriptions 
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
                topic_descriptions
            WHERE 
                topic_descr_id = ? AND status = ?;
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
    public function findByDescription($description)
    {
        $statement = "
            SELECT 
                *
            FROM
                topic_descriptions
            WHERE 
                description = ? AND status = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($description,1));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
    public function findCreatorId($creatorId)
    {
        $statement = "
            SELECT 
                *
            FROM
                topic_descriptions
            WHERE (creator_id = ? ) AND status = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($creatorId,1));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function findByLanguage($languageId)
    {
        $statement = "
            SELECT 
                *
            FROM
                template
            WHERE language_id = ? AND status = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($languageId,1));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function findByCreatorIdLanguageId($creatorId = null, $languageId = null)
    {
        $statement = "
            SELECT 
                *
            FROM
            t   topic_descriptions
            WHERE creator_id = ? AND language_id = ? AND status = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($creatorId,$languageId,1));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO topic_descriptions 
                ( topic_id, description)
            VALUES
                (:topic_id, :description);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':topic_id' => $input['topic_id'],
                ':description' => $input['description']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "

            UPDATE topic_descriptions

            SET 
                topic_id = :topic_id,
                description = :description

            WHERE topic_descr_id = :topic_descr_id AND status = :status;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
               
               ':topic_id' => $input['topic_id'],
               ':description' => $input['description'],
               ':topic_descr_id' => (int) $id,               
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
           
        UPDATE topic_descriptions

        SET 
            status = :status

        WHERE topic_descr_id = :topic_descr_id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('topic_descr_id' => $id,'status'=>0));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    



}


?>
