<?php

class ItemGateway
{
    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input , $num)
    {
        $statement = "
        INSERT INTO `Item`(`name`, `type_id`) VALUES (:name, :typeid); ";
 
        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'name' => $input['name'],
                'typeid' => $input['typeid']
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

    public FindAllItems() 
    {
        $statement = "SELECT Item.name, ItemType.name 
        FROM Item 
        INNER JOIN ItemType ON Item.type_id = ItemType.id
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