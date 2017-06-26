<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    function getNav (Request $request) {
        return success(666);
    }

    function postNav() {
        return success(456);
    }

    function getTest() {
        return success('test indel');
    }

    function getIndex() {
        return success('index');
    }
}
