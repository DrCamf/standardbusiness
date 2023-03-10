<?php


class OrdersGateway 
{
    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input)
    {
        if (array_key_exists('orderNbr', $input)) 
        {
            $statement = "INSERT INTO `Orders`(`orderNbr`, `orderDate`, `user_id`) VALUES (:ordernbr, :orderdate, :userid); ";
            try 
            {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'ordernbr' => $input['ordernbr'],
                    'orderdate' => $input['orderdate'],
                    'userid'  => $input['userid']
                ));
                return $statement->rowCount();
            } 
            catch (\PDOException $e) 
            {
                exit($e->getMessage());
            }    

        } else // if the json had an array
        {
            $statement = "INSERT INTO `Orders`(`orderNbr`, `orderDate`, `user_id`) VALUES ";
            foreach ($input as $item) {
                $statement .=  "('" . $item['ordernbr'] . "', '" . $item['orderdate'] . "', '" . $item['userid'] . "')," ;
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


        $statement = "
        INSERT INTO `Orders`(`orderNbr`, `orderDate`, `user_id`) VALUES (:ordernbr, :orderdate, :userid); ";
 
        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'ordernbr' => $input['ordernbr'],
                'orderdate' => $input['orderdate'],
                'userid'  => $input['userid']
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
        SELECT Users.firstName, Users.lastName, Users.email, `orderNbr`,`orderDate`, `user_id` FROM 
        FROM Orders 
        INNER JOIN Users ON Orders.user_id = Users.id
        WHERE Orders.id = :id;
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

    public function FindAll() 
    {
        $statement = "SELECT `orderNbr`,`orderDate`, `user_id` FROM Orders
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
    }

    public function Update($id, Array $input)
    {
        $statement = "
            UPDATE Orders
            SET 
                `orderNbr`= IsNull(:ordernbr, orderNbr),
                `orderDate`= IsNull(:orderdate, orderDate),
                `user_id`= IsNull(:userid, user_id)
            WHERE id = :id;
        ";

        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'id' => $id,
                'ordernbr' => $input['ordernbr'],
                'orderdate' => $input['orderdate'],
                'userid'  => $input['userid']
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
            DELETE FROM Orders
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