<?php

namespace App\Http\Controllers;

class DataController extends Controller
{
    function getNav () {
        return response()->json('1345');
    }

    function getIndex () {
        return 'index';
    }
}
