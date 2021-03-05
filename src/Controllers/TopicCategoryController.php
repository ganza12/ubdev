<?php

namespace Src\Controllers;

use Src\Models\TopicCategoryModel;
use Src\System\Encript;
use Src\System\Session;
use Src\System\Token;
use Src\System\UiidGeneretor;

class TopicCategoryController{

    private $connection;
    private $requestMethod;
    private $topicCatID;
    private $userAction;  
    private $catModel; 

    public function __construct($connection,$requestMethod,$topicCatID,$userAction){
        $this->connection = $connection;
        $this->requestMethod = $requestMethod;
        $this->topicCatID = $topicCatID;
        $this->userAction = $userAction;
        $this->catModel = new TopicCategoryModel($this->connection);

    }
    // function to process user request and generate response

    public function generateResponse(){

        switch ($this->requestMethod) {

            case 'GET':
                if(isset($this->topicCatID)){

                    $response = $this->getTopicCatById($this->topicCatID);

                }else{

                    $response = $this->getTopicCat();
                    
                }              
                
            break;

            case 'POST':

                $response = $this->createTopicCat();
                
                break;
            
            case 'PUT':

                if($this->userAction === "update_topic_category"){
                    $response = $this->updateTopicCategory($this->topicCatID);
                }else{

                    $response = $this->notFoundResponse();
                }
                
            break;

            case 'DELETE':
                    if($this->userAction == "delete_topic_category"){
                        $response = self::deleteTopicCategory($this->topicCatID);
                    }                    
                    
            break;
        
            default:
                $response = $this->invalidRoute();
                break;
        }
        
            echo $response['body'];

    }

    
    // user controls
    
    private function getTopicCat()
    {
        $result = $this->catModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg"=>"Categories of Topics registered","Data"=>$result]);
        return $response;
    }

    private function getTopicCatById($id)
    {
        $result = $this->catModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg"=>"Categories of templates by Id","Data"=>$result]);
        return $response;
    }

    private function createTopicCat()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateTopicCat($input)) {
            return self::unprocessableEntityResponse();
        }
        // Check if template is  registered
        if($this->catModel->fingByTopicCat($input['topic_category'])){
              return self::alreadyExistist();
        }

            $this->catModel->insert($input);

            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode(['msg' => "successfully registerd {$input['topic_category']}"]);
            return $response;
    }

    private function updateTopicCategory($id)
    {
        $result = $this->catModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateTopicCatUpdateData($input)) {
            return self::unprocessableEntityResponse();
        }
        $this->catModel->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['msg' => 'Data Updated successfully']);
        return $response;
    }


    private function deleteTopicCategory($id)
    {
        $result = $this->catModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $this->catModel->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg" => "Deleted successfully"]);
        return $response;
    }

    private function validateTopicCat($input)
    {
        
        if (empty($input['topic_category'])) {
            return false;
        }
       
        return true;
    }
    private function validateTopicCatUpdateData($input){

        if (empty($input['topic_category'])) {
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
        $response['body'] = json_encode(["status" => 203, "msg" =>"Authotication failed!"]);
        return $response;
    }
    private function alreadyExistist()
    {
        $response['status_code_header'] = 'HTTP/1.1 403 Alred exist';
        $response['body'] = json_encode([   'msg' => 'Already exist']);
        return $response;
    }
    

}

?>