<?php

$host = "localhost";  

     $username = "clvsnhfo_mand";  
 
     $password = "Bobthegod?42";  
 
     $database = "clvsnhfo_StandardBusiness";  
     $dbConnection = null;
echo "outside";

try{
    echo "inside";
   $dbConnection = new \PDO(
        "mysql:host=$host;charset=utf8mb4;dbname=$database",
        $username,
        $password);
}
catch(PDOException $ex){
    die(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
}


?>