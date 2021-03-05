<?php

namespace Src\Controllers;

use Src\Models\TopicDescriptionsModel;
use Src\Models\TopicModel;
use Src\System\Encript;
use Src\System\Session;
use Src\System\Token;
use Src\System\UiidGeneretor;

class TopicDescriptionsController{

    private $connection;
    private $requestMethod;
    private $topicDescriptionId;
    private $userAction;  
    private $templateModel; 
    private $topicModel;

    public function __construct($connection,$requestMethod,$topicDescriptionId,$userAction){
        $this->connection = $connection;
        $this->requestMethod = $requestMethod;
        $this->topicDescriptionId = $topicDescriptionId;
        $this->userAction = $userAction;
        $this->templateModel = new TopicDescriptionsModel($this->connection);
        $this->topicModel = new TopicModel($this->connection);

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
        $result = $this->templateModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg"=>"Topic Descriptions","Data" => $result]);
        return $response;
    }

    private function getDescriptionsById($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createDescription()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateDescription($input)) {
            return self::unprocessableEntityResponse();
        }
        // check if topic is registered 

        if(! $this->topicModel->find($input['topic_id'])){
            // return self::notFoundResponse();
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = json_encode(["status" => 404, "msg" =>"Topic Not Found"]);
            return $response;
        }

        // Check if topic is  registered
        if($this->templateModel->findByDescription($input['description'])){
              return self::alreadyExistist();
        }

            $this->templateModel->insert($input);

            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode(['msg' => "successfully  addedd topic descriptions"]);
            return $response;
    }

    private function updateDescription($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateDescription($input)) {
            return self::unprocessableEntityResponse();
        }
        $this->templateModel->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['msg' => 'Data Updated successfully']);
        return $response;
    }


    private function deleteDescription($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $this->templateModel->delete($id);
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