<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;



class DashboardControllers extends ResourceController
{
    use ResponseTrait;
    public function index()
    {
        return $this->respond(array("status" => true, "messages" => "Dashboard ✅"));
    }
}
