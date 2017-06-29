<?php

namespace App\Http\Controllers;

use App\Models\Nav;
use App\Models\Posts;
use Illuminate\Http\Request;

class DataController extends Controller
{
    function getNav (Request $request) {
        return success(Nav::fetchList());
    }

    function getPosts(Request $request) {
        return success(Posts::paginate(10));
    }
}
