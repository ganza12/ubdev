<?php

namespace Src\Controllers;

use Src\Models\TopicDescriptionsModel;
use Src\Models\TopicModel;
use Src\Models\CreatorsModel;
use Src\Models\TopicCategoryModel;
use Src\System\Encript;
use Src\System\Session;
use Src\System\Token;
use Src\System\UiidGeneretor;

class TopicDescriptionsController{

    private $connection;
    private $requestMethod;
    private $topicDescriptionId;
    private $userAction;  
    private $topicDescModel; 
    private $topicModel;
    private $creatorsModel;
    private $topicCategoryModel;

    public function __construct($connection,$requestMethod,$topicDescriptionId,$userAction){
        $this->connection = $connection;
        $this->requestMethod = $requestMethod;
        $this->topicDescriptionId = $topicDescriptionId;
        $this->userAction = $userAction;
        $this->topicDescModel = new TopicDescriptionsModel($this->connection);
        $this->topicModel = new TopicModel($this->connection);
        $this->creatorsModel = new CreatorsModel($this->connection);
        $this->topicCategoryModel = new TopicCategoryModel($this->connection);

    }
    // function to process user request and generate response

    public function generateResponse(){

        switch ($this->requestMethod) {

            case 'GET':
                if(isset($this->topicDescriptionId)){

                    $response = $this->getDescriptionsById($this->topicDescriptionId);

                }else{

                    $response = $this->getAllDescriptions();
                    
                }              
                
            break;

            case 'POST':

                $response = $this->createDescription();
                
                break;
            
            case 'PUT':

                if($this->userAction === "update_description"){
                    $response = $this->updateDescription($this->topicDescriptionId);
                }else{

                    $response = $this->notFoundResponse();
                }
                
            break;

            case 'DELETE':
                    if(empty($this->userAction)){
                        $response = self::incorrectRoute();
                    }
                    if($this->userAction == "delete_description_info"){
                        $response = self::deleteDescription($this->topicDescriptionId);
                    }                    
                    
            break;
        
            default:
                $response = $this->invalidRoute();
                break;
        }
        
            echo $response['body'];

    }

    
    // user controls
    
    private function getAllDescriptions()
    {
        $description = $this->topicDescModel->findAll();
        $descriptionResults = [];
        $descrObj = new \stdClass();

        foreach($description as $key=> $value){
   
            // find topic information
            $topicData = $this->topicModel->find($value['topic_id']);

            foreach($topicData as $keys => $topic){
                $descrObj->topic_descr_id = $value['topic_descr_id'];
                $descrObj->description = $value['description'];
                $descrObj->date_created = $value['date_created'];
                $descrObj->topic_title = $topic['topic_title'];

            // find creator information

            $creatorData = $this->creatorsModel->find($topic['creator_id']);

            foreach($creatorData as $keyss => $creator){
                $descrObj->creator_names = $creator['names'];
                $descrObj->creator_email = $creator['email'];
            }

            // find category information

            $categoryData = $this->topicCategoryModel->find($topic['topic_category_id']);

            foreach($categoryData as $keysss => $category){
                $descrObj->category_name = $category['topic_category'];
            }

            }
            $descJson = json_encode($descrObj);           


            array_push($descriptionResults, json_decode($descJson));
            
        }
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';

        $response['body'] = json_encode(["msg"=>"Topic Descriptions","Data" => $descriptionResults]);
        return $response;
    }

    private function getDescriptionsById($id)
    {
        
       // $result = $this->topicDescModel->find($id);

        $description = $this->topicDescModel->find($id);
        $descriptionResults = [];
        $descrObj = new \stdClass();

        foreach($description as $key=> $value){           
            
            
            // find topic information
            $topicData = $this->topicModel->find($value['topic_id']);

            foreach($topicData as $keys => $topic){
                $descrObj->topic_descr_id = $value['topic_descr_id'];
                $descrObj->description = $value['description'];
                $descrObj->date_created = $value['date_created'];
                $descrObj->topic_title = $topic['topic_title'];

            // find creator information

            $creatorData = $this->creatorsModel->find($topic['creator_id']);

            foreach($creatorData as $keyss => $creator){
                $descrObj->creator_names = $creator['names'];
                $descrObj->creator_email = $creator['email'];
            }

            // find category information

            $categoryData = $this->topicCategoryModel->find($topic['topic_category_id']);

            foreach($categoryData as $keysss => $category){
                $descrObj->category_name = $category['topic_category'];
            }

            }
            $descJson = json_encode($descrObj);           


            array_push($descriptionResults, json_decode($descJson));
        }
        if (! $descriptionResults) {
            return self::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($descriptionResults);
        return $response;
    }

    private function createDescription()
    {

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateDescription($input)) {
            return self::unprocessableEntityResponse();
        }
        // check if user is logged in
        if(empty($_SESSION)){
            return self::AuthFail();
        }

        // check if topic is registered 

        if(! $this->topicModel->find($input['topic_id'])){
            // return self::notFoundResponse();
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = json_encode(["status" => 404, "msg" =>"Topic Not Found"]);
            return $response;
        }

        // Check if topic is  registered
        if($this->topicDescModel->findByDescription($input['description'])){
              return self::alreadyExistist();
        }

            $this->topicDescModel->insert($input);

            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode(['msg' => "successfully  addedd topic descriptions"]);
            return $response;
    }

    private function updateDescription($id)
    {
         // check if user is logged in
         if(empty($_SESSION)){
            return self::AuthFail();
        }

        $result = $this->topicDescModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateDescription($input)) {
            return self::unprocessableEntityResponse();
        }
        $this->topicDescModel->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['msg' => 'Data Updated successfully']);
        return $response;
    }


    private function deleteDescription($id)
    {
         // check if user is logged in
         if(empty($_SESSION)){
            return self::AuthFail();
        }

        $result = $this->topicDescModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $this->topicDescModel->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg" => "Data Deleted successfully"]);
        return $response;
    }

    private function validateDescription($input)
    {
        if (empty($input['topic_id'])) {
            return false;
        }
        if (empty($input['description'])) {
            return false;
        }
       
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([  'error' => 'Invalid input' ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(["status" => 404, "msg" =>"Not Found"]);
        return $response;
    }

    private function AuthFail()
    {
        $response['status_code_header'] = 'HTTP/1.1 203 Non-Authoritative Information!';
        $response['body'] = json_encode(["status" => 203, "msg" =>"Authentication failed!"]);
        return $response;
    }
    private function alreadyExistist()
    {
        $response['status_code_header'] = 'HTTP/1.1 403 Already exist';
        $response['body'] = json_encode([   'msg' => 'template Already exist try to change its description']);
        return $response;
    }
    private function incorrectRoute(){
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(["status" => 404, "msg" =>"Incorrect Routes or Path"]);
        return $response;
    }
    

}

?>