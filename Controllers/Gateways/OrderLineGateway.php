<?php

class OrderLineGateway 
{
    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input , $num)
    {
        $statement = "
        INSERT INTO `OrderLine`( `amount`, `price`, `order_id`, `item_id`) VALUES (:amount, :price, :orderid, :itemid); ";
 
        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'amount' => $input['amount'],
                'price' => $input['price'],
                'orderid'  => $input['orderid'],
                'itemid'  => $input['itemid']
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
        SELECT amount, price, Orders.orderNbr, Item.name FROM 
        FROM OrderLine 
        INNER JOIN Item ON OrderLine.item_id = Item.id
        INNER JOIN Orders ON OrderLine.order_id = Orders.id
        WHERE OrderLine.id = :id;
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
        $statement = "SELECT amount, price, Orders.orderNbr, Item.name
        INNER JOIN Item ON OrderLine.item_id = Item.id
        INNER JOIN Orders ON OrderLine.order_id = Orders.id
        FROM OrderLine
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
            UPDATE OrderLine
            SET 
                `amount`= IsNull(:amount, amount),
                `price`= IsNull(:price, price),
                `order_id`= IsNull(:orderid, order_id),
                `item_id`= IsNull(:itemid, item_id)
            WHERE id = :id;
        ";

        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'id' => $id,
                'amount' => $input['amount'],
                'price' => $input['price'],
                'orderid'  => $input['orderid'],
                'itemid'  => $input['itemid']
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
            DELETE FROM OrderLine
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