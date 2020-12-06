<?php namespace Controllers;
use Exception;
use mysqli;
use Services\Service;


class BaseController extends Mysqli{
    private string $host='localhost';
    private string $user='root';
    private string $password='';
    private string $database = 'test';
    private ?string $port = NULL;
    private ?string $socket = NULL;
    public function __construct() {

        parent::__construct($this->host, $this->user, $this->password, $this->database, $this->port, $this->socket);

        $this->throwConnectionExceptionOnConnectionError();
    }

    private function throwConnectionExceptionOnConnectionError() {
        try {
            if (!$this->connect_error) return;
            $message = sprintf('(%s) %s', $this->connect_errno, $this->connect_error);
            throw  new Exception($message);
        }catch (Exception $ex){
            Service::endResponse(data:$ex,message:$ex->getMessage(),status: 0);

        }

    }
}
