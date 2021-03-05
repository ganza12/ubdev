<?php

namespace Src\Models;

class TemplatesModel{

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
                templates 
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
                templates
            WHERE 
                template_id = ? AND status = ?;
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
                templates
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
                templates
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
            t   templates
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
            INSERT INTO templates 
                ( creator_id, template_name, category_id,language_id, description, template_file)
            VALUES
                (:creator_id, :template_name, :category_id, :language_id, :description, :template_file);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':creator_id' => $input['creator_id'],
                ':template_name' => $input['template_name'],
                ':category_id' => $input['category_id'],
                ':language_id' => $input['language_id'],
                ':description' => $input['description'],
                ':template_file' => $input['template_file']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "

            UPDATE templates

            SET 
                template_name = :template_name,
                category_id = :category_id,
                language_id = :language_id,
                description = :description,
                template_file = :template_file

            WHERE template_id = :template_id AND status = :status;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
               
               ':template_name' => $input['template_name'],
               ':category_id' => $input['category_id'],
               ':language_id' => $input['language_id'],
               ':description' => $input['description'],
               ':template_file' => $input['template_file'],
               ':template_id' => (int) $id,               
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
            DELETE FROM templates
            WHERE template_id = :template_id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('template_id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    



}


?>
