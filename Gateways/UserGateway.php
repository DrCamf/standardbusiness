<?php
class UserGateway 
{

    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input )
    {
        if (array_key_exists('firstname', $input)) 
        {
            $statement = "INSERT INTO `Users`(`firstName`, `lastName`, `tlfnr`, `email`, `adress`, `zip_id`, `type_id`, `account_id`) 
            VALUES (:firstname, :lastname, :tlfnr, :email, :adress, :zipid, :typeid, :accountid);";
            try 
            {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'firstname' => $input['firstname'] ,
                    'lastname'  => $input['lastname'],
                    'tlfnr' => $input['tlfnr'] ,
                    'email' => $input['email'] ?? null,
                    'adress' => $input['adress'] ,
                    'zipid' => $input['zipid'] ,
                    'typeid' => $input['typeid'] ,
                    'accountid' => $input['accountid'] 
    
                ));
                return $statement->rowCount();
            } 
            catch (\PDOException $e) 
            {
                exit($e->getMessage());
            }    

        } else // if the json had an array
        {
            $statement = "INSERT INTO `Users`(`firstName`, `lastName`, `tlfnr`, `email`, `adress`, `zip_id`, `typeid`, `accountid`)  VALUES";
            foreach ($input as $item) {
                $statement .=  "('" . $item['firstname'] . "', '" . $item['lastname'] . "', '" . $item['tlfnr'] . "', '" . $item['email'] . 
                "', '" .  $item['adress'] . "', '" . $item['zipid'] . "', '" . $item['typeid'] . "', '" . $item['accountid'] ."')," ;
            }
            $statement = substr($statement, 0, -1);

            try 
            {
                $statement = $this->db->prepare($statement);
                $statement->execute();
                return $statement->rowCount();
            } 
            catch (\PDOException $e) 
            {
                exit($e->getMessage());
            }    
        }
    }

    function AccountInsert(Array $input) 
    {
        $statement = "INSERT INTO Account(password) VALUES (:password);";
        $param_password = password_hash($input['password'], PASSWORD_DEFAULT);
        try {
        
            $statement = $this->db->prepare($statement);
            $statement->bindParam('password',  $param_password);
            $statement->execute();
            
            $num = $statement->rowCount();
            
        } catch (\PDOException $e) {

            exit($e->getMessage());
        }  
       
         $statement = " SELECT MAX(id) FROM `Account`;";
         
          try {
        
            $statement = $this->db->prepare($statement);
           
            $statement->execute();
            
           $num = $statement->fetchColumn();
            return $num;
        } catch (\PDOException $e) {

            exit($e->getMessage());
        }  
    }

    public function Find($id)
    {
       
        $statement ="
        SELECT SELECT `firstName` AS FirstName, `lastName` AS LastName, `tlfnr` AS PHONE, `email` AS EMAIL, `adress` AS Adress, City.zip AS ZIP,  City.name AS CITY, UserType.name AS TYPE
        FROM Users 
        INNER JOIN City ON Users.zip_id = City.id
        INNER JOIN UserType ON Users.type_id = UserType.id 
        WHERE Users.id = :id;
        ";

        try {

            $statement = $this->db->prepare($statement);
            $statement->bindParam('id',  $id);
            $statement->execute();

            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if(! $result) 
            {
                echo "noo result";
            }

            return $result;
       

        } catch (\PDOException $e) 
        {
            exit($e->getMessage());
        }    
    }

    public function FindAll() 
    {
        $statement = "SELECT `firstName` AS FirstName, `lastName` AS LastName, `tlfnr` AS PHONE, `email` AS EMAIL, `adress` AS Adress, City.zip AS ZIP,  City.name AS CITY, UserType.name AS TYPE
        FROM Users 
        INNER JOIN City ON Users.zip_id = City.id
        INNER JOIN UserType ON Users.type_id = UserType.id; "; //evt order by 

        try {

            $statement = $this->db->prepare($statement);
            $statement->execute();

            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
        

        } catch (\PDOException $e) {

            exit($e->getMessage());
        }    
    }

    public function Update($id, Array $input)
    {
        $statement = "
            UPDATE Users
            SET 
                `firstName`= IsNull(:firstname, firstName),
                `lastName`= IsNull(:lastname, lastName),
                `tlfnr`= IsNull(:tlfnr, tlfnr),
                `email`= IsNull(:email, email),
                `adress`= IsNull(:lastname, lastName),:,
                `zip_id`= IsNull(:zipid, zip_id),
                `type_id`= IsNull(:typeid, type_id),
                `account_id`= IsNull(:accountid, account_id)
            WHERE id = :id;
        ";

        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'id' => $id,
                'firstname' => $input['firstname'] ,
                'lastname'  => $input['lastname'],
                'tlfnr' => $input['tlfnr'] ,
                'email' => $input['email'] ?? null,
                'adress' => $input['adress'] ,
                'zipid' => $input['zipid'] ,
                'typeid' => $input['typeid'] ,
                'accountid' => $input['accountid'] 
            ));
            return $statement->rowCount();
        
        } catch (\PDOException $e) 
        {
            exit($e->getMessage());
        }    
    }

    public function Delete($id)
    {
        $statement = "
            DELETE FROM Users
            WHERE id = :id;
        ";

        try 
        {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();

        } catch (\PDOException $e) 
        {
            exit($e->getMessage());
        }    
    }

}

?>