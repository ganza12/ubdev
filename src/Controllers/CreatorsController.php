<?php

namespace Src\Controllers;

use Src\Models\CreatorsModel;
use Src\System\Encript;
use Src\System\Session;
use Src\System\Token;
use Src\System\UiidGeneretor;

class CreatorsController{

    private $connection;
    private $requestMethod;
    private $userId;
    private $userAction;  
    private $creatorModel; 

    public function __construct($connection,$requestMethod,$userId,$userAction){
        $this->connection = $connection;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->userAction = $userAction;
        $this->creatorModel = new CreatorsModel($this->connection);

    }
    // function to process user request and generate response

    public function generateResponse(){

        switch ($this->requestMethod) {

            case 'GET':
                if(isset($this->userId)){

                    $response = $this->getCreatorsById($this->userId);

                }else{

                    $response = $this->getAllCreators();
                    
                }              
                
            break;

            case 'POST':

                $response = $this->createNewUser();
                
                break;
            
            case 'PUT':

                if($this->userAction === "update_creator_info"){
                    $response = $this->updateUser($this->userId);
                }else if (($this->userAction === "change_password")){
                    $response = self::updateUserPassword($this->userId);
                }else{
                    $response = $this->notFoundResponse();
                }
                
            break;

            case 'DELETE':
                    if($this->userAction == "delete_creator_info"){
                        $response = self::deleteUser($this->userId);
                    }                    
                    
            break;
        
            default:
                $response = $this->invalidRoute();
                break;
        }
        
            echo $response['body'];

    }

    
    // user controls
    
    private function getAllCreators()
    {
        $result = $this->creatorModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getCreatorsById($id)
    {
        $result = $this->creatorModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getByEmailUsername($name){

        $result = $this->creatorModel->findByEmailUsername($name);
        if (! $result) {
            return self::notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
        
    }

    private function createNewUser()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateUser($input)) {
            return self::unprocessableEntityResponse();
        }
        // Check if creator is  registered

        if($this->creatorModel->findByEmailUsername($input['email'],$input['username'])) {
            $response['status_code_header'] = 'HTTP/1.1 403 Alred exist';
            $response['body'] = json_encode([   'msg' => 'User Already exist']);
            return $response;
        }
            $input['password'] = Encript::saltEncription($input['password']);
            $this->creatorModel->insert($input);

            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode(['msg' => 'User Account Created successfully'  ]);
            return $response;
    }

    private function updateUser($id)
    {
        $result = $this->creatorModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateCreatorData($input)) {
            return self::unprocessableEntityResponse();
        }
        $this->creatorModel->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['msg' => 'Updated successfully']);
        return $response;
    }

    private function updateUserPassword($id)
    {

        $result = $this->creatorModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (empty($input['old_password'])) {
            return self::unprocessableEntityResponse();
        }
        // Check if user is registered

        $old_password = Encript::saltEncription($input['old_password']);

        if($result[0]["password"] != $old_password){
            $response['status_code_header'] = 'HTTP/1.1 400 Bad request';
            $response['body'] = json_encode(["msg" => "Old password is not correct"]);
            return $response;
        }
        if(empty($input['new_password'])){
            $response['status_code_header'] = 'HTTP/1.1 400 Bad request';
            $response['body'] = json_encode(["msg" => "New password is required"]);
            return $response;
        }
        $input['new_password'] = Encript::saltEncription($input['new_password']);

        $this->creatorModel->updatePassword($id, $input);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg" => "user password changed Successfully"]);
        return $response;
    }
    private function deleteUser($id)
    {
        $result = $this->creatorModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $this->creatorModel->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg" => "User Account Deleted successfully"]);
        return $response;
    }

    private function validateUser($input)
    {
        if (empty($input['names'])) {
            return false;
        }

        if (empty($input['username'])) {
            return false;
        }
        if (empty($input['password'])) {
            return false;
        }
        if (empty($input['email'])) {
            return false;
        }
       
        return true;
    }
    private function validateCreatorData($input){

        if (empty($input['names'])) {
            return false;
        }

        if (empty($input['username'])) {
            return false;
        }
        if (empty($input['email'])) {
            return false;
        }
        return true;

    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
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
    

}

?>