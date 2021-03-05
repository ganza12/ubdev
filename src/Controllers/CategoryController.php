<?php

namespace Src\Controllers;

use Src\Models\CategoryModel;
use Src\System\Encript;
use Src\System\Session;
use Src\System\Token;
use Src\System\UiidGeneretor;

class CategoryController{

    private $connection;
    private $requestMethod;
    private $categoryId;
    private $userAction;  
    private $catModel; 

    public function __construct($connection,$requestMethod,$categoryId,$userAction){
        $this->connection = $connection;
        $this->requestMethod = $requestMethod;
        $this->categoryId = $categoryId;
        $this->userAction = $userAction;
        $this->catModel = new CategoryModel($this->connection);

    }
    // function to process user request and generate response

    public function generateResponse(){

        switch ($this->requestMethod) {

            case 'GET':
                if(isset($this->categoryId)){

                    $response = $this->getCategoryById($this->categoryId);

                }else{

                    $response = $this->getCategory();
                    
                }              
                
            break;

            case 'POST':

                $response = $this->createCategory();
                
                break;
            
            case 'PUT':

                if($this->userAction === "update_category"){
                    $response = $this->updateCategory($this->categoryId);
                }else{

                    $response = $this->notFoundResponse();
                }
                
            break;

            case 'DELETE':
                    if($this->userAction == "delete_category"){
                        $response = self::deleteCategory($this->categoryId);
                    }                    
                    
            break;
        
            default:
                $response = $this->invalidRoute();
                break;
        }
        
            echo $response['body'];

    }

    
    // user controls
    
    private function getCategory()
    {
        $result = $this->catModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg"=>"Categories of templates registered","Data"=>$result]);
        return $response;
    }

    private function getCategoryById($id)
    {
        $result = $this->catModel->find($id);
        if (! $result) {
            return self::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["msg"=>"Categories of templates by Id","Data"=>$result]);
        return $response;
    }

    private function createCategory()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateCategory($input)) {
            return self::unprocessableEntityResponse();
        }
        // Check if template is  registered
        if($this->catModel->findBycategoryame($input['category_name'])){
              return self::alreadyExistist();
        }

            $this->catModel->insert($input);

            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode(['msg' => "successfully registerd {$input['category_name']}"]);
            return $response;
    }

    private function updateCategory($id)
    {
        $result = $this->catModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! self::validateCategoryUpdateData($input)) {
            return self::unprocessableEntityResponse();
        }
        $this->catModel->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(['msg' => 'Data Updated successfully']);
        return $response;
    }


    private function deleteCategory($id)
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

    private function validateCategory($input)
    {
        
        if (empty($input['category_name'])) {
            return false;
        }
       
        return true;
    }
    private function validateCategoryUpdateData($input){

        if (empty($input['category_name'])) {
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