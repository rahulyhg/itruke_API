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
		$id = $request->id;
		$info = Tag::find($id);
		return success($info);
	}

	function postTag(Request $request) {
		$name = $request->input('name');
		$id = Tag::where('name', '<>', $name)->insertGetId(['name' => $name]);
		return success(['id' => $id]);
	}
}