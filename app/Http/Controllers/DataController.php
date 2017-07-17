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
        $q = Posts::orderBy('addTime', 'desc');
        $navId = $request->get('navId');
        if (!empty($navId)) {
            $q->where('navId', $navId);
        }
        return success($q->paginate(10));
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
        return success(Link::where('status',2)->orderBy('timestamp')->get());
    }
}
