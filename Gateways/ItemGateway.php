<?php

class ItemGateway
{
    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input )
    {
        if (array_key_exists('name', $input)) {
            $statement = "INSERT INTO `Item`(`name`, `type_id`) VALUES (:name, :typeid); ";
            try 
            {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'name' => $input['name'],
                    'typeid' => $input['typeid']
                ));
                return $statement->rowCount();
            } 
            catch (\PDOException $e) 
            {
                exit($e->getMessage());
            }    
    
            
        }else // if the json had an array
        {
            $statement = "INSERT INTO `Item`(`name`, `type_id`) VALUES";
            foreach ($input as $item) {
                $statement .=  "('" . $item['name'] . "', '" .  $item['typeid']. "')," ;
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
        SELECT Item.name, ItemType.name  
        FROM Item 
        INNER JOIN ItemType ON Item.type_id = ItemType.id
        WHERE Item.id = :id;
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
        $statement = "SELECT Item.name, ItemType.name 
        INNER JOIN ItemType ON Item.type_id = ItemType.id
        FROM Item 
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
            UPDATE Item
            SET 
                `name`= IsNull(:name, name),
                `type_id`= IsNull(:typeid, type_id)
            WHERE id = :id;
        ";

        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'id' => $id,
                'name' => $input['name'],
                'typeid' => $input['typeid']
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
            DELETE FROM Item
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