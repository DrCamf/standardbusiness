<?php
include_once 'gateways/ElevGateway.php';

include_once 'config/DatabaseConnector.php';

class ElevController {

    private $db;

    private $requestMethod;

    private $input;



    private $eventGateway;



    public function __construct( $requestMethod, $input)
    {

        $db = new DatabaseConnector();

        $this->requestMethod = $requestMethod;

        $this->input = $input;
        

        $this->elevGateway = new ElevGateway($db->getConnection());
    }



    public function processRequest()
    {

        switch ($this->requestMethod) {

           case 'GET':

                if ($this->input) {

                    $response = $this->getElev($this->input);

                } /*else {

                    $response = $this->getAllEvents();

                };*/

                break;

            case 'POST':

                $response = $this->createElevFromRequest();

                break;

           /* case 'PUT':

                $response = $this->updateEventFromRequest($this->input);

                break;

            case 'DELETE':

                $response = $this->deleteEvent($this->input);

                break;

            default:

                $response = $this->notFoundResponse();

                break;*/

        }

        header($response['status_code_header']);

        if ($response['body']) {

            echo $response['body'];

        }

    }


    private function createElevFromRequest()
    {

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        /*$input1 =  json_decode($input);*/

       //print_r($input);

        /*if (! $this->validateEvent($input1)) {

            return $this->unprocessableEntityResponse();

        }*/
        
        $num = $this->elevGateway->loginInsert($input);
        
        $this->elevGateway->insert($input, $num);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';

        $response['body'] = null;

        return $response;

    }

    private function getElev($id)
    {

        $result = $this->elevGateway->findElev($id);

        /*if (! $result) {
            return $this->notFoundResponse();
        }*/

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }


    private function validateEvent($input)
    {

        if (! isset($input['title'])) {

            return false;
        }

        if (! isset($input['place'])) {
            return false;
        }

        return true;
    }



    private function unprocessableEntityResponse()
    {

        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';

        $response['body'] = json_encode([

            'error' => 'Invalid input'

        ]);

        return $response;
    }



    private function notFoundResponse()
    {

        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';

        $response['body'] = null;

        return $response;

    }















}


?>