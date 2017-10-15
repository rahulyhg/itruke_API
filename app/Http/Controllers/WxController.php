<?php
/**
 * Created by LEYA.
 * User: 陈老二
 * Email: chenJiao@163.com
 * Date: 2017/10/15
 * Time: 16:36
 */
namespace App\Http\Controllers;

class WxController extends Controller
{
	function serve (Request $request) {
		$wx = app('wechat');
		$wx->server->setMessageHandler(function ($message) {
			return '技术宅男子,欢迎';
		});
		return $wx->server->serve();
	}
}