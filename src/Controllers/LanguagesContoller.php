<?php

namespace Src\Controllers;

use Src\Models\LanguagesModel;
use Src\System\Encript;
use Src\System\Session;
use Src\System\Token;
use Src\System\UiidGeneretor;

class LanguagesContoller{

    private $connection;
    private $requestMethod;
    private $languageId;
    private $userAction;  
    private $templateModel; 

    public function __construct($connection,$requestMethod,$languageId,$userAction){
        $this->connection = $connection;
        $this->requestMethod = $requestMethod;
        $this->languageId = $languageId;
        $this->userAction = $userAction;
        $this->templateModel = new LanguagesModel($this->connection);

    }
    // function to process user request and generate response

    public function generateResponse(){

        switch ($this->requestMethod) {

            case 'GET':
                if(isset($this->languageId)){

                    $response = $this->getLanguageByID($this->languageId);

                }else{

                    $response = $this->getLanguages();
                    
                }              
                
            break;

            case 'POST':

                $response = $this->createNewLanguage();
                
                break;
            
            case 'PUT':

                if($this->userAction === "update_language"){
                    $response = $this->updateLanguage($this->languageId);
                }else{

                    $response = $this->notFoundResponse();
                }
                
            break;

            case 'DELETE':
                    if($this->userAction == "delete_language"){
                        $response = self::deleteUser($this->languageId);
                    }                    
                    
            break;
        
            default:
                $response = $this->invalidRoute();
                break;
        }
        
            echo $response['body'];

    }

    
    // user controls
    
    private function getLanguages()
    {
        $result = $this->templateModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getLanguageByID($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createNewLanguage()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateLanguage($input)) {
            return self::unprocessableEntityResponse();
        }
        // Check if template is  registered
        if($this->templateModel->findByLanguageName($input['language_name'])){
              return self::alreadyExistist();
        }

            $this->templateModel->insert($input);

            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode(['msg' => "successfully registerd {$input['language_name']}"]);
            return $response;
    }

    private function updateLanguage($id)
    {
        $result = $this->templateModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateLanguageUpdateData($input)) {
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
        $response['body'] = json_encode(["msg" => "language Deleted successfully"]);
        return $response;
    }

    private function validateLanguage($input)
    {
        
        if (empty($input['language_name'])) {
            return false;
        }
       
        return true;
    }
    private function validateLanguageUpdateData($input){

        if (empty($input['language_name'])) {
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