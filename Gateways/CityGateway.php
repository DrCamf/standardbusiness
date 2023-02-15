<?php

class CityGateway 
{
    private $db = null;

    public function __construct($db)  { $this->db = $db; }

    public function Insert(Array $input )
    {
        if (array_key_exists('zip', $input)) {
            $statement = "INSERT INTO `City`(`zip`, `name`) VALUES (:zip, :name); ";
            try 
            {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'zip' => $input['zip'],
                    'name' => $input['name']
                ));
                return $statement->rowCount();
            } 
            catch (\PDOException $e) 
            {
                exit($e->getMessage());
            }    
    
        } else // if the json had an array
        {
            $statement = "INSERT INTO `City`(`zip`, `name`) VALUES";
            foreach ($input as $item) {
                $statement .=  "('" . $item['zip'] . "', ' " . $item['name'] .  "')," ;
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
        SELECT zip, name  
        FROM City 
        WHERE id = :id;
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
        $statement = "SELECT zip, name
        FROM City 
        
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
            UPDATE City
            SET 
                `zip`= IsNull(:zip, zip),
                `name`= IsNull(:name, name)
            WHERE id = :id;
        ";

        try 
        {
            $statement = $this->db->prepare($statement);

            $statement->execute(array(
                'id' => $id,
                'zip' => $input['zip'],
                'name' => $input['name']
                
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
            DELETE FROM City
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