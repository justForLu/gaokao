<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

class IndexController extends BaseController
{


    public function __construct(Request $request)
    {
        parent::__construct($request);

    }


    public function index()
    {
        echo 'Hello World!';
        die();
    }


}




