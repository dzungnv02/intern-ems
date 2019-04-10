<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\CompensatedClass;
use \App\Classes;
use \App\Student;

class CompensatedClassController extends Controller
{
    /**
     * List & search
     */
    public function index(Request $request) 
    {
        $condition = [];
        return response()->json(CompensatedClass::search($condition), 200);
    }

    public function getById($id)
    {

    }

    public function create(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function delete($id)
    {

    }
}
