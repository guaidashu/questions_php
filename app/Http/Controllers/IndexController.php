<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    function __construct()
    {
    }

    public function index()
    {
        return view('index');
    }
}
