<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Nav;
use App\Models\Posts;
use App\Models\Reply;
use App\Models\Tag;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

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

    function getReply(Request $request) {
        $list = Reply::where('key', $request->get('key'))->orderBy('path')->orderBy('addTime', 'desc')->get();
        return success($list);
    }

    function postReply(Request $request) {
        $pid = $request->get('pid');
        if ($pid == 0) {
            $level = 0;
        } else {
            $info = Reply::find($pid);
            $level = $info->level + 1;
        }
        $ip = $request->getClientIp();
        $content = $request->get('content');
        $key = $request->get('key');
        $userId = $request->userId;

        $brower = Agent::browser();
        $v1 = Agent::version($brower);
        $platform = Agent::platform();
        $version = Agent::version($platform);
        $tool = $brower.' '.$v1;
        $os = $platform.' '.$version;
        $rid = Reply::insertGetId(['pid'=>$pid, 'key'=>$key,'level'=>$level, 'path'=>'', 'content'=>$content, 'userId'=>$userId, 'ip'=>$ip, 'os'=>$os, 'tool'=>$tool]);
        if ($pid == 0) {
            Reply::whereId($rid)->update(['path'=>'0,'.$rid]);
        } else {
            Reply::whereId($rid)->update(['path'=>$info->path.','.$rid]);
        }
        return success();
    }
}
