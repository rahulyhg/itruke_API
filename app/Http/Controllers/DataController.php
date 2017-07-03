<?php

namespace App\Http\Controllers;

use App\Models\Nav;
use App\Models\Posts;
use Illuminate\Http\Request;

class DataController extends Controller
{
    function getNav (Request $request) {
        if (!empty($request->input('id'))) {
            $info = Nav::find($request->input('id'));
            return success($info);
        }
        return success(Nav::fetchList());
    }

    function getPosts(Request $request) {
        return success(Posts::paginate(10));
    }
}
