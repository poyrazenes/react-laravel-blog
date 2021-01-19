<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Response\Response;

class BaseController extends Controller
{
    protected $response = null;

    public function __construct()
    {
        $this->response = new Response();
    }
}
