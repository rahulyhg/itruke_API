<?php
/**
 * Created by LEYA.
 * User: 陈老二
 * Email: chenJiao@163.com
 * Date: 2017/6/26
 * Time: 15:10
 */
namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Nav;
use App\Models\Posts;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
	function getNav (Request $request) {
		if (!empty($request->input('id'))) {
			$info = Nav::find($request->input('id'));
			return success($info);
		}
		return success(Nav::fetchList());
	}

	function getNavList(Request $request) {
		$list = Nav::all();
		$ret = [];
		foreach ($list as $l) {
			if (empty(Nav::subNav($l->id))){
				$ret[] = $l;
			}
		}
		return success($ret);
	}

	function getPosts(Request $request) {
		$page_size = $request->input('page_size') ? $request->input('page_size') : 10;
		$id = $request->input('id');
		if (!empty($id)) {
			return success(Posts::find($id));
		}
		return success(Posts::orderBy('addTime', 'desc')->paginate($page_size));
	}

	function getLink(Request $request) {
		$page_size = $request->input('page_size') ? $request->input('page_size') : 10;
		$id = $request->input('id');
		if (!empty($id)) {
			return success(Link::find($id));
		}
		return success(Link::orderBy('timestamp')->paginate($page_size));
	}

	function getTag(Request $request) {
		$find = $request->input('find');
		if (!empty($find)) {
			return success(Tag::where('name', 'like', '%'.$find.'%')->get());
		}
		$id = $request->input('id');
		if (!empty($id)) {
			$info = Tag::find($id);
			return success($info);
		}
		return success(Tag::all());
	}

	function postTag(Request $request) {
		$name = $request->input('name');
		$id = Tag::where('name', '<>', $name)->insertGetId(['name' => $name]);
		return success(['id' => $id]);
	}

	function postLink(Request $request) {
		$title = $request->get('title');
		$desc = $request->get('desc');
		$url = $request->get('url');
		$status = $request->get('status');
		if (empty($title) || empty($url)) {
			return error('url和title必须');
		}
		Link::insert(['title'=>$title, 'url'=>$url, 'status'=>$status, 'desc'=>$desc]);
		return success();
	}

	function putLink (Request $request) {
		$title = $request->get('title');
		$desc = $request->get('desc');
		$url = $request->get('url');
		$status = $request->get('status');
		if (empty($title) || empty($url)) {
			return error('url和title必须');
		}
		Link::whereId($request->get('id'))->update(['title'=>$title, 'url'=>$url, 'status'=>$status, 'desc'=>$desc]);
		return success();
	}

	function deleteLink(Request $request) {
		Link::where('id',$request->get('id'))->delete();
		return success();
	}

	function deletePosts(Request $request) {
		Posts::where('id',$request->get('id'))->delete();
		return success();
	}

	function postPosts(Request $request) {
		$title = $request->input('title');
		$content = $request->input('content');
		$desc = $request->input('desc');
		$tags = json_encode($request->input('tags'));
		$imgs = json_encode($request->input('imgs'));
		$navId = $request->input('navId');
		$author = $request->input('author');
		Posts::insert(['title'=>$title, 'content'=>$content, 'desc'=>$desc, 'tags'=>$tags, 'imgs'=>$imgs, 'navId'=>$navId, 'author'=>$author]);
		return success();
	}

	function putPosts(Request $request) {
		$title = $request->input('title');
		$content = $request->input('content');
		$desc = $request->input('desc');
		$tags = json_encode($request->input('tags'));
		$imgs = json_encode($request->input('imgs'));
		$navId = $request->input('navId');
		$author = $request->input('author');
		$id = $request->get('id');
		Posts::where('id', $id)->update(['title'=>$title, 'content'=>$content, 'desc'=>$desc, 'tags'=>$tags, 'imgs'=>$imgs, 'navId'=>$navId, 'author'=>$author]);
		return success();
	}

	function postNav(Request $request) {
		$name = $request->input('name');
		$pid = $request->input('pid');
		if (empty($name) || empty($pid)) {
			return error('请完整填写');
		}
		$has = Nav::where('name', $name)->first();
		if ($has && $has->id) {
			return error('已经存在的分类', 500);
		}
		Nav::insert(['name'=>$name, 'pid'=>$pid]);
	}

	function putNav(Request $request) {
		$name = $request->input('name');
		$pid = $request->input('pid');
		$id = $request->input('id');
		if (empty($name) || empty($pid)) {
			return error('请完整填写');
		}
		$info = Nav::find($id);
		if (!empty($info)) {
			$info->name = $name;
			$info->pid = $pid;
			$info->save();
		}
		return success();
	}

	function getUser(Request $request) {
		return success(User::all());
	}

	function deleteNav (Request $request) {
		$id = $request->input('id');
		$count = Posts::where('navId', $id)->count();
		if ($count > 0) {
			return error('该分类下有文章,不支持删除');
		}
		Nav::where('id', $id)->delete();
		return success();
	}

	function postUpload (Request $request) {
		$file = $request->file('file');
		if(!empty($file) && $file->isValid()){
			$file_name = md5_file($file->getRealPath());
			if(!file_exists(getenv('UPLOAD_DIR').$file_name)){
				$file->move(getenv('UPLOAD_DIR'),$file_name);
			}
			return success(['url'=>getenv('FILE_PATH').$file_name]);
		}else{
			return error(500,'请选择图片');
		}
	}

	function postLogin (Request $request) {
		if (md5($request->get('password')) == '667bfb2d66fa79699ee2ace21d1863af') {
			$token = Cache::has('token') ? Cache::get('token') : md5(time());
			Cache::add('token', $token, 60);
			return success($token);
		} else {
			return error('login error');
		}
	}
}
