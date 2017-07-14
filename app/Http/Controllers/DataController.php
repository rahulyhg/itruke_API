<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Nav;
use App\Models\Posts;
use App\Models\Tag;
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
        if (!empty($request->input('id'))) {
            return success(Posts::find($request->input('id')));
        }
        return success(Posts::orderBy('addTime', 'desc')->paginate(10));
    }

    function getTag(Request $request) {
        $id = $request->get('id');
        $info = Tag::find($id);
        if (!empty($id))
            return success($info);
        else
            return success(Tag::orderBy('addTime','desc')->get());
    }

    function getLink(Request $request) {
        return success(Link::orderBy('timestamp')->get());
    }
}
