<?php


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
