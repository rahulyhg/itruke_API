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
		return success(Posts::all());
	}

	function getTag(Request $request) {
		$id = $request->id;
		$info = Tag::find($id);
		return success($info);
	}
}