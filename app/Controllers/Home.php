<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Home extends ResourceController
{
    protected $format = 'json';

    use ResponseTrait;
    public function index()
    {
        // $output = array("status" => true, "message" => "Welcome");
        return $this->respond(array("status" => true, "messages" => "Welcome"));
    }
}
