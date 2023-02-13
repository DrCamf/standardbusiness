<?php

class ElevGateway{

    private $db = null;

   

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insert(Array $input , $num)
    {

        $statement = "
        INSERT INTO `Elever`(`elev_NR`, `name`, `postNR`, `cpr`, `email`, `stamklasse_id`, `blacklisted`, `login_id`, `adresse`, `grundBlacklisted`) 
        VALUES (:elevnr, :name, :postNR, :cpr, :email, :stamklasseid, :blacklisted, :loginid, :adresse, 'tom'); ";
 
        try {
          
           
            $statement = $this->db->prepare($statement);

            $statement->execute(array(

                'elevnr' => $input['elevnr'] ,
                'name'  => $input['name'],
                'postNR' => $input['postNR'] ,
                'cpr' => $input['cpr'] ?? null,
                'email' => $input['email'] ?? null,
                'stamklasseid' => $input['stamklasseid'] ,
                'blacklisted' => 0,
                'loginid' => $num,
                'adresse' => $input['adresse'] ?? null

            ));

            return $statement->rowCount();

        } catch (\PDOException $e) {

            exit($e->getMessage());

        }    

    }

     function loginInsert(Array $input) {
        $statement = "INSERT INTO Login(password, role_id) VALUES (:password, :role);";
        $param_password = password_hash($input['password'], PASSWORD_DEFAULT);
        try {
        
            $statement = $this->db->prepare($statement);
            $statement->bindParam('password',  $param_password);
            $statement->bindParam('role', $input['roleid']);
            $statement->execute();
            
            $num = $statement->rowCount();
            
        } catch (\PDOException $e) {

            exit($e->getMessage());
        }  
       
         $statement = " SELECT MAX(id) FROM `Login`;";
         
          try {
        
            $statement = $this->db->prepare($statement);
           
            $statement->execute();
            
           $num = $statement->fetchColumn();
            return $num;
        } catch (\PDOException $e) {

            exit($e->getMessage());
        }  
    }

    public function findElev($id){
       
        $statement ="
        SELECT elev_NR, name, postNR, cpr, email, stamklasse_id, blacklisted, login_id, grundBlacklisted 
        FROM Elever WHERE elev_NR = :id;
        ";

        try {

            $statement = $this->db->prepare($statement);
            $statement->bindParam('id',  $id);
            $statement->execute();

            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
           

        } catch (\PDOException $e) {

            exit($e->getMessage());
        }    
    }
}

?>