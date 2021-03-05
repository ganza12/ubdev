<?php

namespace Src\Controllers;

use Src\Models\TopicModel;
use Src\Models\CreatorsModel;
use Src\System\Encript;
use Src\System\Session;
use Src\System\Token;
use Src\System\UiidGeneretor;

class TopicController{

    private $connection;
    private $requestMethod;
    private $topicId;
    private $userAction;  
    private $templateModel;
    private $creatorsModels; 

    public function __construct($connection,$requestMethod,$topicId,$userAction){
        $this->connection = $connection;
        $this->requestMethod = $requestMethod;
        $this->topicId = $topicId;
        $this->userAction = $userAction;
        $this->templateModel = new TopicModel($this->connection);
        $this->creatorsModels= new CreatorsModel($this->connection);

    }
    // function to process user request and generate response

    public function generateResponse(){

        switch ($this->requestMethod) {

            case 'GET':
                if(isset($this->topicId)){

                    $response = $this->getTopicById($this->topicId);

                }else{

                    $response = $this->getAllTopic();
                    
                }              
                
            break;

            case 'POST':

                $response = $this->createTopic();
                
                break;
            
            case 'PUT':

                if($this->userAction === "update_topic"){
                    $response = $this->updateTopic($this->topicId);
                }else{

                    $response = $this->notFoundResponse();
                }
                
            break;

            case 'DELETE':
                    if($this->userAction == "delete_topic_info"){
                        $response = self::deleteUser($this->topicId);
                    }                    
                    
            break;
        
            default:
                $response = $this->invalidRoute();
                break;
        }
        
            echo $response['body'];

    }

    
    // user controls
    
    private function getAllTopic()
    {
        $result = $this->templateModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg"=>"All Registered Topics","Data" => $result]);
        return $response;
    }

    private function getTopicById($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createTopic()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateTopic($input)) {
            return self::unprocessableEntityResponse();
        }
        // Check if topic  is  registered
        if($this->templateModel->findByTopicTitle($input['topic_title'])){
              return self::alreadyExistist($input['topic_title']);
        }

        // check if user exists
        if(!($this->creatorsModels->find($input['creator_id']))){
            return self::notFoundResponse();
        }

            $this->templateModel->insert($input);

            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode(['msg' => "successfully registerd topic: {$input['topic_title']}"]);
            return $response;
    }

    private function updateTopic($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateTopicData($input)) {
            return self::unprocessableEntityResponse();
        }

        if(!($this->creatorsModels->find($input['creator_id']))){
            return self::notFoundResponse();
        }

        $this->templateModel->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['msg' => 'Data Updated successfully']);
        return $response;
    }


    private function deleteUser($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $this->templateModel->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg" => "Topic  Deleted successfully"]);
        return $response;
    }

    private function validateTopic($input)
    {
        if (empty($input['creator_id'])) {
            return false;
        }
        if (empty($input['topic_title'])) {
            return false;
        }
       
        return true;
    }
    private function validateTopicData($input){

        if (empty($input['creator_id'])) {
            return false;
        }
        if (empty($input['topic_title'])) {
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
        $response['body'] = json_encode(["status" => 404, "msg" =>" Creator Not Found "]);
        return $response;
    }

    private function AuthFail()
    {
        $response['status_code_header'] = 'HTTP/1.1 203 Non-Authoritative Information!';
        $response['body'] = json_encode(["status" => 203, "msg" =>"Authotication failed!"]);
        return $response;
    }
    private function alreadyExistist($component)
    {
        $response['status_code_header'] = 'HTTP/1.1 403 Alred exist';
        $response['body'] = json_encode([   'msg' => "topic entitled: {$component} Already exists try new One"]);
        return $response;
    }
    

}

?>