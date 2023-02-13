<?php

class BoughtGateWay
{
    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input , $num)
    {
        $statement = "
        INSERT INTO `ItemBought`(`price`, `volume`, `item_id`, `boughtDate`) VALUES (:price, :volume, :itemid, :boughtdate); ";
 
        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'price' => $input['price'],
                'volume' => $input['volume'],
                'itemid' => $input['itemid'],
                'boughtdate' => $input['boughtdate']
            ));

            return $statement->rowCount();

        } catch (\PDOException $e) 
        {
            exit($e->getMessage());
        }    
    }

    public function FindItem($id)
    {
        $statement ="
        SELECT `price`, `volume`, Item.name, `boughtDate` 
        FROM ItemBought 
        INNER JOIN Item ON ItemBought.item_id = Item.id
        WHERE ItemBought.id = :id;
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

    public FindAllItems() 
    {
        $statement = "SELECT `price`, `volume`, Item.name, `boughtDate` 
        FROM ItemBought 
        INNER JOIN Item ON ItemBought.item_id = Item.id
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
            UPDATE ItemBought
            SET 
                `price`= IsNull(:price, price),
                `volume`= IsNull(:volume, volume),
                `item_id`= IsNull(:itemid, item_id),
                `boughtDate`= IsNull(:boughtdate, boughtDate)
            WHERE id = :id;
        ";

        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'id' => $id,
                'price' => $input['price'],
                'volume' => $input['volume'],
                'itemid' => $input['itemid'],
                'boughtdate' => $input['boughtdate']
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
            DELETE FROM ItemBought
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