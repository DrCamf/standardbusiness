<?php

class BoughtGateWay
{
    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input)
    {
        if (array_key_exists('price', $input)) {
            $statement = "INSERT INTO `ItemBought`(`price`, `volume`, `item_id`, `boughtDate`) 
            VALUES (:price, :volume, :itemid, :boughtdate); ";
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
            } 
            catch (\PDOException $e) 
            {
                exit($e->getMessage());
            }    
    
        } else // if the json had an array
        {
            $statement = "INSERT INTO `ItemBought`(`price`, `volume`, `item_id`, `boughtDate`) VALUES";
            foreach ($input as $item) {
                $statement .=  "('" . $item['price'] . "','" .  $item['volume'] ."', '" . $item['itemid'] . "', '" . $item['boughtdate'] . "'  )," ;
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

    public function Find($id)
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

    public function FindAll() 
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