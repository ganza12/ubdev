<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: *');
// header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: *');


require 'bootstrap.php';

use Src\Controllers\AuthorizationController;
use Src\Controllers\TokenController;
use Src\Controllers\CreatorsController;
use Src\Controllers\TemplatesController;
use Src\Controllers\LanguagesContoller;
use Src\Controllers\TopicController;
use Src\Controllers\TopicDescriptionsController;
use Src\Controllers\CategoryController;
use Src\Controllers\TopicCategoryController;


// extracting url

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// switching user routse
if(isset($uri[4])){
switch ($uri[4]) {

    case 'session':
        // Check token:
        $creatorrSession = null;
        if(sizeof($uri) > 4){
            if (isset($uri[5])) {
                $creatorrSession = $uri[5];
            }
        }
        $requestMethod = $_SERVER["REQUEST_METHOD"];
    
        // pass the request method and user ID to the SessionController and process the HTTP request:
        //$controller = new TokenController($requestMethod,$creatorrSession);
        //$controller->processRequest();
        echo json_encode($_SESSION);
    break;
    case 'creators':

        $creatorId = null;
        $userAction = null;
        
        if(isset($uri)){

            if(sizeof($uri) > 4){
                if(isset($uri[5])){
                    if(is_numeric($uri[5])){
                        $creatorId = (int)$uri[5];
                    }
                }
                if(isset($ur[6])){
                    if(is_numeric($uri[6])){
                        $creatorId = $uri[6];
                    }
                }
                if(isset($uri[5])){
                    if(is_string($uri[5])){
                        $userAction = $uri[5];
                    }
                }

            }

        }

        $redirect = explode('/',$_SERVER['REDIRECT_QUERY_STRING']);

            // Routing
            $userRoute = explode('/',"/api/creators/change_password/:id");

            if(sizeof($redirect) == sizeof($userRoute)){
                $creatorId = $redirect[4];
                $userAction = $redirect[3];               
                
            }

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $controller = new CreatorsController($connection,$requestMethod,$creatorId,$userAction);
        $controller->generateResponse();
        
        break;

        case 'login':
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $controller = new AuthorizationController($connection,$requestMethod);
            $controller -> generateResponse();

        break;

        case 'template':

            $templateId = null;
            $userAction = null;
            
            if(isset($uri)){
    
                if(sizeof($uri) > 4){
                    if(isset($uri[5])){
                        if(is_numeric($uri[5])){
                            $templateId = (int)$uri[5];
                        }
                    }
                    if(isset($ur[6])){
                        if(is_numeric($uri[6])){
                            $templateId = $uri[6];
                        }
                    }
                    if(isset($uri[5])){
                        if(is_string($uri[5])){
                            $userAction = $uri[5];
                        }
                    }
    
                }
    
            }
    
            $redirect = explode('/',$_SERVER['REDIRECT_QUERY_STRING']);
    
                // Routing
                $userRoute = explode('/',"/api/template/update_template/:id");
    
                if(sizeof($redirect) == sizeof($userRoute)){
                    $templateId = $redirect[4];
                    $userAction = $redirect[3];               
                    
                }
    
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $controller = new TemplatesController($connection,$requestMethod,$templateId,$userAction);
            $controller->generateResponse();
            
        break;

        case 'language':

            $languageId = null;
            $userAction = null;
            
            if(isset($uri)){
    
                if(sizeof($uri) > 4){
                    if(isset($uri[5])){
                        if(is_numeric($uri[5])){
                            $languageId = (int)$uri[5];
                        }
                    }
                    if(isset($ur[6])){
                        if(is_numeric($uri[6])){
                            $languageId = $uri[6];
                        }
                    }
                    if(isset($uri[5])){
                        if(is_string($uri[5])){
                            $userAction = $uri[5];
                        }
                    }
    
                }
    
            }
    
            $redirect = explode('/',$_SERVER['REDIRECT_QUERY_STRING']);
    
                // Routing
                $userRoute = explode('/',"/api/language/update_language/:id");
    
                if(sizeof($redirect) == sizeof($userRoute)){
                    $languageId = $redirect[4];
                    $userAction = $redirect[3];               
                    
                }
    
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $controller = new LanguagesContoller($connection,$requestMethod,$languageId,$userAction);
            $controller->generateResponse();
            
            break;


        case 'topic':

            $topicId = null;
            $userAction = null;
            
            if(isset($uri)){
    
                if(sizeof($uri) > 4){
                    if(isset($uri[5])){
                        if(is_numeric($uri[5])){
                            $topicId = (int)$uri[5];
                        }
                    }
                    if(isset($ur[6])){
                        if(is_numeric($uri[6])){
                            $topicId = $uri[6];
                        }
                    }
                    if(isset($uri[5])){
                        if(is_string($uri[5])){
                            $userAction = $uri[5];
                        }
                    }
    
                }
    
            }
    
            $redirect = explode('/',$_SERVER['REDIRECT_QUERY_STRING']);
    
                // Routing
                $userRoute = explode('/',"/api/language/update_topic/:id");
    
                if(sizeof($redirect) == sizeof($userRoute)){
                    $topicId = $redirect[4];
                    $userAction = $redirect[3];               
                    
                }
    
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $controller = new TopicController($connection,$requestMethod,$topicId,$userAction);
            $controller->generateResponse();
            
            break;

            case 'description':

                $topicDescriptionId = null;
                $userAction = null;
                
                if(isset($uri)){
        
                    if(sizeof($uri) > 4){
                        if(isset($uri[5])){
                            if(is_numeric($uri[5])){
                                $topicDescriptionId = (int)$uri[5];
                            }
                        }
                        if(isset($ur[6])){
                            if(is_numeric($uri[6])){
                                $topicDescriptionId = $uri[6];
                            }
                        }
                        if(isset($uri[5])){
                            if(is_string($uri[5])){
                                $userAction = $uri[5];
                            }
                        }
        
                    }
        
                }
        
                $redirect = explode('/',$_SERVER['REDIRECT_QUERY_STRING']);
        
                    // Routing
                    $userRoute = explode('/',"/api/language/update_description/:id");
        
                    if(sizeof($redirect) == sizeof($userRoute)){
                        $topicDescriptionId = $redirect[4];
                        $userAction = $redirect[3];               
                        
                    }
        
                $requestMethod = $_SERVER["REQUEST_METHOD"];
                $controller = new TopicDescriptionsController($connection,$requestMethod,$topicDescriptionId,$userAction);
                $controller->generateResponse();
                
            break;
            case 'category':

                $categoryId = null;
                $userAction = null;
                
                if(isset($uri)){
        
                    if(sizeof($uri) > 4){
                        if(isset($uri[5])){
                            if(is_numeric($uri[5])){
                                $categoryId = (int)$uri[5];
                            }
                        }
                        if(isset($ur[6])){
                            if(is_numeric($uri[6])){
                                $categoryId = $uri[6];
                            }
                        }
                        if(isset($uri[5])){
                            if(is_string($uri[5])){
                                $userAction = $uri[5];
                            }
                        }
        
                    }
        
                }
        
                $redirect = explode('/',$_SERVER['REDIRECT_QUERY_STRING']);
        
                    // Routing
                    $userRoute = explode('/',"/api/category/update_category/:id");
        
                    if(sizeof($redirect) == sizeof($userRoute)){
                        $categoryId = $redirect[4];
                        $userAction = $redirect[3];               
                        
                    }
        
                $requestMethod = $_SERVER["REQUEST_METHOD"];
                $controller = new CategoryController($connection,$requestMethod,$categoryId,$userAction);
                $controller->generateResponse();
                
            break;

            case 'topicCat':

                $topicCatID = null;
                $userAction = null;
                
                if(isset($uri)){
        
                    if(sizeof($uri) > 4){
                        if(isset($uri[5])){
                            if(is_numeric($uri[5])){
                                $topicCatID = (int)$uri[5];
                            }
                        }
                        if(isset($ur[6])){
                            if(is_numeric($uri[6])){
                                $topicCatID = $uri[6];
                            }
                        }
                        if(isset($uri[5])){
                            if(is_string($uri[5])){
                                $userAction = $uri[5];
                            }
                        }
        
                    }
        
                }
        
                $redirect = explode('/',$_SERVER['REDIRECT_QUERY_STRING']);
        
                    // Routing
                    $userRoute = explode('/',"/api/category/update_topic_category/:id");
        
                    if(sizeof($redirect) == sizeof($userRoute)){
                        $topicCatID = $redirect[4];
                        $userAction = $redirect[3];               
                        
                    }
        
                $requestMethod = $_SERVER["REQUEST_METHOD"];
                $controller = new TopicCategoryController($connection,$requestMethod,$topicCatID,$userAction);
                $controller->generateResponse();
                
            break;


            default:

                echo invalidRoute(); 

            break;
            }

        }else{
            echo invalidRoute(); 
            }


function invalidRoute(){
    $response['status_code_header'] = 'HTTP/1.1 404 page Not Found';
    $response['body'] = json_encode(["status" => 404, "message" =>"Invalid request"]);
    return $response['body'];

}



?>