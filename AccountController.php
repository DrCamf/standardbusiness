<?php

include_once 'DbConn.php';

include_once 'Gateways/AccountGateway.php';


class AccountController
{
    private $db;
    private $requestMethod;
    private $input;
    private $accountgateway;

    public function __construct( $requestMethod, $input)
    {
        $db = new DbConn();

        $this->requestMethod = $requestMethod;
        $this->input = $input;

        $this->accountgateway = new AccountGateway($db->getConnection());
    }

    public function processRequest()
    {
        switch ($this->requestMethod) 
        {
           case 'GET':
                if ($this->input) 
                {
                    $response = $this->GetOneFromRequest($this->input);
                } 

                break;

            case 'POST':
                $response = $this->CreateFromRequest();
                break;

            case 'PUT':
                $response = $this->UpdateFromRequest($this->input);
                break;

            case 'DELETE':
                $response = $this->Delete($this->input);
                break;

            default:
                $response = $this->NotFoundResponse();
                break;
        }

        header($response['status_code_header']);

        if ($response['body']) 
        {
            echo $response['body'];
        }
    }

    private function CreateFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        /*if(count($input) > 1) {
            
            foreach ($input as $item){
                $this->usertypegateway->Insert($item['name']);
                sleep(0.1);
            }
         
        } else {*/
            $this->accountgateway->Insert($input);
        
       
        
        /*if (! $this->validateEvent($input1)) {

            return $this->unprocessableEntityResponse();

        }*/
                
       

        $response['status_code_header'] = 'HTTP/1.1 201 Created';

        $response['body'] = null;

        return $response;

    }

    private function GetOneFromRequest($id)
    {
        $result = $this->accountgateway->Find($id);

        /*if (! $result) {
            return $this->notFoundResponse();
        }*/

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function GetAll()
    {
        $result = $this->accountgateway->FindAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
       
        return $response;
    }

    private function UpdateFromRequest($id)
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $this->accountgateway->Update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function Delete($id)
    {
        $result = $this->accountgateway->find($id);
        if (! $result) {
            return $this->NotFoundResponse();
        }
        $this->accountgateway->Delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }


    /*private function validateEvent($input)
    {
        if (! isset($input['title'])) 
        {
            return false;
        }

        if (! isset($input['place'])) 
        {
            return false;
        }

        return true;
    }*/
    // evt flyt til selvstændig fil
    private function UnprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);

        return $response;
    }

    private function NotFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}

?>

