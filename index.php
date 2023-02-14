<?php

include_once 'Controllers/UserController.php';
include_once 'Controllers/ItemController.php';
include_once 'Controllers/OrdersController.php';
include_once 'Controllers/BoughtController.php';
include_once 'Controllers/ItemTypeController.php';
include_once 'Controllers/CityController.php';
include_once 'Controllers/UserTypeController.php';
include_once 'Controllers/OrderLineController.php';

//C:\xampp\htdocs\standardbusiness\Controllers\UserController.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//Getting what gateway to use
$pass = "";
$item = "";
$id = 0;
// Getting id and if the is an item that too
if ($_GET) {
    $pass = $_GET['pass'];
    $id = $_GET['id'];
   if(isset($_GET["item"])){
        $item = $_GET["item"];
    }else {
        $item = "";
    }   
} else {
    $id = 0;
}

//Getting what method is requested it could be get, post, put or delete
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch($pass) 
{
    case 'user':
        $controller = new UserController($requestMethod, $id);
        break;
        
    case 'orders':
        $controller = new OrdersController($requestMethod, $id);
        break;
            
    case 'item':
        $controller = new ItemController($requestMethod, $id);
        break;
        
    case 'bought':
        $controller = new BoughtController($requestMethod, $id);
        break;
            
    case 'city':
        $controller = new CityController($requestMethod, $id);
        break;
                
    case 'itemtype':
        $controller = new ItemTypeController($requestMethod, $id);
        break;

    case 'orderline':
        $controller = new OrderLineController($requestMethod, $id);
        break;
                    
    case 'usertype':
        $controller = new UserTypeController($requestMethod, $id);
        break;

    default:
        echo "none";
        break;
}


$controller->processRequest();
?>