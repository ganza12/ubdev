<?php

namespace Src\Models;

class TopicModel{

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
                topic 
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
                topic
            WHERE 
                topic_id = ? AND status = ?;
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
    public function findByCreatorId($creatorId)
    {
        $statement = "
            SELECT 
                *
            FROM
                topic
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


    public function findByTopicTitle($title)
    {
        $statement = "
            SELECT 
                *
            FROM
                topic
            WHERE topic_title = ? AND status = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($title,1));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO topic 
                (creator_id,topic_category_id, topic_title)
            VALUES
                (:creator_id,:topic_category_id, :topic_title);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':creator_id' => $input['creator_id'],
                ':topic_category_id' => $input['topic_category_id'],
                ':topic_title' => $input['topic_title'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "

            UPDATE topic

            SET 
                topic_title = :topic_title

            WHERE topic_id = :topic_id AND status = :status;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
               
               ':topic_title' => $input['topic_title'],
               ':topic_id' => (int) $id,               
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

            UPDATE topic

            SET 
                status = :status

            WHERE topic_id = :topic_id;
            
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':topic_id' => (int) $id,               
                 ':status' => 0
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    



}


?>
