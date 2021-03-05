<?php

namespace Src\Controllers;

use Src\Models\CreatorsModel;
use Src\System\Encript;
use Src\System\Session;
use Src\System\Token;
use Src\System\UiidGeneretor;


class AuthorizationController{

    private $connection;
    private $requestMethod;
    private $creatorModel;
   
    public function __construct($connection,$requestMethod){

        $this->connection = $connection;
        $this->requestMethod = $requestMethod;
        $this->creatorModel = new CreatorsModel($this->connection);      

    }
    // function to process user request and generate response

    public function generateResponse(){

        switch ($this->requestMethod) {

            case 'POST':

                $response = self::creatorsLoginRequest();

            break;

            case 'DELETE':

                $response = self::logOut();

            break;
            
            default:
               $response = self::notFoundResponse();
                break;
        }
            echo $response['body'];

    }


    private function creatorsLoginRequest(){
        $input = (array) json_decode(file_get_contents('php://input'), TRUE); 
        // Validate input if not empty
        if(! self::validateCreator($input)){
            return self::unprocessableEntityResponse();
        }
        // Found if user is has account
        if($result = $this->creatorModel->findByEmailUsername($input['username'])) {
            $input_password = Encript::saltEncription($input['password']);

            if($input_password === $result[0]['password']){
                Session::put("creator_id",$result[0]['creator_id']);
                Session::put("names",$result[0]['names']);
                Session::put("username",$result[0]['username']);
                Session::put("email",$result[0]['email']);

                if(Token::generate("USER_TOKEN")){
                    Token::setTokenExpire();
                }

                $response['status_code_header'] = 'HTTP/1.1 200 success!';
                $response['body'] = json_encode([
                'msg' => $_SESSION
                ]);
                return $response;
            }
            $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
            $response['body'] = json_encode([
            'msg' => 'Username/password does not match'
            ]);
            return $response;
        }else{
            $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
            $response['body'] = json_encode([
            'msg' => 'Username/password does not match'
            ]);
            return $response;
        }


    }

    private function logOut() {
        Session::destroy();
        
        $response['status_code_header'] = 'HTTP/1.1 200 ok';
        $response['body'] = json_encode(["status" => 200, "msg" =>"Successfully loged out user"]);
        return $response;
    }


    private function validateCreator($input)
    {
        if (empty($input['username'])) {
            return false;
        }
        if (empty($input['password'])) {
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

   
    

}

?>