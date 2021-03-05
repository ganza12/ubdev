<?php

namespace Src\Models;

class TopicCategoryModel{

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
                topic_category 
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
                topic_category
            WHERE 
                topic_category_id = ? AND status = ?;
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

        // find by topic_category 

        public function fingByTopicCat($topic_categoryame)
        {
            $statement = "
                SELECT 
                    *
                FROM
                    topic_category 
                WHERE 
                    topic_category = ? AND status = ?;
            ";
    
            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array($topic_categoryame,1));
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                return $result;
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }    
        }
      
  
    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO topic_category

                (topic_category)

            VALUES

                (:topic_category);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':topic_category' => $input['topic_category'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "

            UPDATE topic_category
            SET 
                topic_category = :topic_category,
                 status = :status

            WHERE topic_category_id = :topic_category_id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
               
               ':topic_category' => $input['topic_category'],
               ':topic_category_id' => (int) $id,               
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
            DELETE FROM

                     topic_category

            WHERE 
            
                    topic_category_id = :topic_category_id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('topic_category_id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    



}


?>
