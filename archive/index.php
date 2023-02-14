<?php 

include_once 'ElevController.php';
include_once 'TeacherController.php';
include_once 'ComputerController.php';
include_once 'UdlaanController.php';
include_once 'SearchController.php';
include_once 'ReserveringController.php';

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

//
switch($pass) {

    case 'underviser':
        $controller = new TeacherController($requestMethod, $id);
        break;

    case 'elev':
        $controller = new ElevController($requestMethod, $id);
        break;

    case 'computer':
        $controller = new ComputerController($requestMethod, $id);
        break;

    case 'udlaan':
        $controller = new UdlaanController($requestMethod, $id);
        break;

    case 'reserver':
        $controller = new ReserveringController($requestMethod, $id);
        break;

    case 'search':
       
        $controller = new SearchController($requestMethod, $item ,$id);
        break;
    
    /*default:
       $controller = new UdlaanController($requestMethod, $id);
        break;*/
}

$controller->processRequest();

?>

