<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function __construct()
    {
    }

    public function getData()
    {
        return successReply();
    }
}
