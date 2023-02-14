<?php

class AccountGateway 
{
    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input , $num)
    {
        $statement = "
        INSERT INTO `Account`(`password`) VALUES (:password); ";
 
        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'password' => $input['password']                
            ));

            return $statement->rowCount();

        } catch (\PDOException $e) 
        {
            exit($e->getMessage());
        }    
    }

    public function Find($id)
    {
        $statement ="
        SELECT password  
        FROM Account 
        INNER JOIN Users ON Users.account_id = Account.id
        WHERE Users.id = :id;
        ";

        try 
        {
            $statement = $this->db->prepare($statement);
            $statement->bindParam('id',  $id);
            $statement->execute();

            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            return $result;

        } catch (\PDOException $e) 
        {
            exit($e->getMessage());
        }    
    }

    /*public FindAllItems() 
    {
        $statement = "SELECT name
        FROM UserType 
       
        ; "; //evt order by 

        try 
        {
            $statement = $this->db->prepare($statement);
            $statement->execute();

            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
        
        } catch (\PDOException $e) 
        {
            exit($e->getMessage());
        }    
    }*/

    public function Update($id, Array $input)
    {
        $statement = "
            UPDATE Account
            SET 
                `password`= IsNull(:password, password),
            INNER JOIN Users ON Users.account_id = Account.id
                WHERE Users.id = :id;
        ";

        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'id' => $id,
                'password' => $input['password']                
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
            DELETE FROM Account
            INNER JOIN Users ON Users.account_id = Account.id
            WHERE Users.id = :id;
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