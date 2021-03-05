<?php

namespace Src\Controllers;

use Src\Models\TemplatesModel;
use Src\System\Encript;
use Src\System\Session;
use Src\System\Token;
use Src\System\UiidGeneretor;

class TemplatesController{

    private $connection;
    private $requestMethod;
    private $templateId;
    private $userAction;  
    private $templateModel; 

    public function __construct($connection,$requestMethod,$templateId,$userAction){
        $this->connection = $connection;
        $this->requestMethod = $requestMethod;
        $this->templateId = $templateId;
        $this->userAction = $userAction;
        $this->templateModel = new TemplatesModel($this->connection);

    }
    // function to process user request and generate response

    public function generateResponse(){

        switch ($this->requestMethod) {

            case 'GET':
                if(isset($this->templateId)){

                    $response = $this->getTemplatesById($this->templateId);

                }else{

                    $response = $this->getAllTemplates();
                    
                }              
                
            break;

            case 'POST':

                $response = $this->createTemplates();
                
                break;
            
            case 'PUT':

                if($this->userAction === "update_template"){
                    $response = $this->updateTemplateInfo($this->templateId);
                }else{

                    $response = $this->notFoundResponse();
                }
                
            break;

            case 'DELETE':
                    if($this->userAction == "delete_template_info"){
                        $response = self::deleteUser($this->templateId);
                    }                    
                    
            break;
        
            default:
                $response = $this->invalidRoute();
                break;
        }
        
            echo $response['body'];

    }

    
    // user controls
    
    private function getAllTemplates()
    {
        $result = $this->templateModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg" => "All templates" ,"Data" =>$result]);
        return $response;
    }

    private function getTemplatesById($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createTemplates()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateTepmlate($input)) {
            return self::unprocessableEntityResponse();
        }
        // Check if template is  registered
        if($this->templateModel->findByDescription($input['description'])){
              return self::alreadyExistist();
        }

            $this->templateModel->insert($input);

            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode(['msg' => "successfully registerd template {$input['template_name']}"]);
      
            return $response;
    }

    private function updateTemplateInfo($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateTemplateData($input)) {
            return self::unprocessableEntityResponse();
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
        $response['body'] = json_encode(["msg" => "template data Deleted successfully"]);
        return $response;
    }

    private function validateTepmlate($input)
    {
        if (empty($input['creator_id'])) {
            return false;
        }
        if (empty($input['template_name'])) {
            return false;
        }
        if (empty($input['category_id'])) {
            return false;
        }

        if (empty($input['language_id'])) {
            return false;
        }
        if (empty($input['description'])) {
            return false;
        }
        if (empty($input['template_file'])) {
            return false;
        }
       
        return true;
    }
    private function validateTemplateData($input){

        if (empty($input['template_name'])) {
            return false;
        }
        
        if (empty($input['category_id'])) {
            return false;
        }

        if (empty($input['language_id'])) {
            return false;
        }

        if (empty($input['description'])) {
            return false;
        }
        if (empty($input['template_file'])) {
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
        $response['body'] = json_encode([   'msg' => 'template Already exist try to change its description']);
        return $response;
    }
    

}

?>