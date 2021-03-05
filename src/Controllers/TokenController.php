<?php 

namespace Src\Controllers;

use Src\System\Token;

class  TokenController{
    private $requestMethod;

    public function __construct($requestMethod) {
        $this->requestMethod = $requestMethod;
    }
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':

                    echo json_encode($_SESSION);//$response = self::getTokenAndSession();

                break;
            default:
                $response = self::notFoundResponse();
                break;
        }
        // header($response['status_code_header']);
        // if ($response['body']) {
        //     echo $response['body'];
        // }
    }
    public function getTokenAndSession() {

        // if(isset($_SESSION['email'])){
            $response['status_code_header'] = 'HTTP/1.1 200 success!';
            $response['body'] = json_encode($_SESSION);
            return $response;
        // }else{
        //     $response['status_code_header'] = 'HTTP/1.1 203 Non-Authoritative Information!';
        //     $response['body'] = json_encode(["msg" => "no creator login history!"]);
        //     return $response;
        // }
    }
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(["status" => 404, "msg" =>"Not Found"]);
        return $response;
    }

}
?>