<?php
class UserGateway {

    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input , $num)
    {
        $statement = "
        INSERT INTO `Users`(`firstName`, `lastName`, `tlfnr`, `email`, `adress`, `zip_id`, `type_id`, `account_id`) 
        VALUES (:firstname, :lastname, :tlfnr, :email, :adress, :zip_id, :type_id, :account_id); ";
 
        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'firstname' => $input['firstname'] ,
                'lastname'  => $input['lastname'],
                'tlfnr' => $input['tlfnr'] ,
                'email' => $input['email'] ?? null,
                'adress' => $input['adress'] ,
                'zip_id' => $input['zip_id'] ,
                'type_id' => $input['ztype_id'] ,
                'account_id' => $input['account_id'] 

            ));

            return $statement->rowCount();

        } catch (\PDOException $e) 
        {
            exit($e->getMessage());
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

    public function FindUser($id){
       
        $statement ="
        SELECT `firstName`, `lastName`, `tlfnr`, `email`, `adress`, City.zip, City.name, UserType.name
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

            return $result;
           

        } catch (\PDOException $e) {

            exit($e->getMessage());
        }    
    }

    public FindAllUser() {
        $statement = "SELECT `firstName`, `lastName`, `tlfnr`, `email`, `adress`, City.zip, City.name, UserType.name
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
                `zip_id`= IsNull(:zip_id, zip_id),
                `type_id`= IsNull(:type_id, type_id),
                `account_id`= IsNull(:account_id, account_id)
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
                'zip_id' => $input['zip_id'] ,
                'type_id' => $input['type_id'] ,
                'account_id' => $input['account_id'] 
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