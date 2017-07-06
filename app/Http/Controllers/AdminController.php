<?php
/**
 * Created by LEYA.
 * User: 陈老二
 * Email: chenJiao@163.com
 * Date: 2017/6/26
 * Time: 15:10
 */
namespace App\Http\Controllers;

use App\Models\Nav;
use App\Models\Posts;
use App\Models\Tag;
use Illuminate\Http\Request;

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
		return success(Nav::all());
	}

	function getPosts(Request $request) {
		$page_size = $request->input('page_size') ? $request->input('page_size') : 10;
		$id = $request->input('id');
		if (!empty($id)) {
			return success(Posts::find($id));
		}
		return success(Posts::paginate($page_size));
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
}