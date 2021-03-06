<?php
/**
 * Created by HuaTu.
 * User: 陈仁焕
 * Email: ruke318@gmail.com
 * Date: 2018/3/5
 * Time: 17:45
 * Desc: [github第三方登录]
 */
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GithubController
{
	const URL = 'https://github.com/login/oauth/authorize?client_id=:client_id&state=:state&redirect_uri=:redirect_uri';
	const CLIENT_ID = '570414f100c3f00b3a26';
	const SECRET = '437fc854e44185f5bce7ca57118a6f120b4ca520';
	const STATE = 'tifjxiHfdajfad9fdasfU9423';
	const ACCESS_URL = 'https://github.com/login/oauth/access_token?client_id=:client_id&client_secret=:client_secret&code=:code&redirect_uri=:redirect_uri';
	const USER_INFO = 'https://api.github.com/user?access_token=';
	const API_HOST = 'http://po.ly';

	static function getCode($back)
	{
		$redirect_uri = self::API_HOST.'/github/callback?back='.$back;
		$url = self::URL;
		$url = str_replace([':client_id', ':state', ':redirect_uri'], [self::CLIENT_ID, self::STATE, $redirect_uri], $url);
		return redirect($url);
	}

	function getIndex(Request $request)
	{
		$backUrl = $request->get('back');
		if (empty($backUrl)) {
			return error('回调地址必须');
		}
		return self::getCode($backUrl);
	}

	function getCallback(Request $request)
	{
		$code = $request->code;
		$back = $request->get('back');
		$url = self::ACCESS_URL;
		$redirect_uri = self::API_HOST.'/github/callback';
		if ($code) {
			$url = str_replace([':client_id', ':client_secret', ':redirect_uri', ':code'], [self::CLIENT_ID, self::SECRET, $redirect_uri, $code], $url);
			$res = http_get($url);
			$access_token = '';
			parse_str($res);
			$info_link = self::USER_INFO.$access_token;
			$user_info = http_get($info_link, 300, ['User-Agent:ruke318', 'Accept: application/json']);
			$user_info = json_decode($user_info);
			$has_user = User::where('openid', $user_info->id)->where('userResource', 'github')->first();
			if (!$has_user) {
				$user_id = User::insertGetId([
					'openid' => $user_info->id,
					'name' => $user_info->name,
					'avatar' => $user_info->avatar_url,
					'addTime' => date('Y-m-d H:i:s'),
					'url' => $user_info->html_url,
					'userResource' => 'github'
				]);
			} else {
				$user_id = $has_user->id;
			}
			$t = time();
			Cache::add($t, $user_id, 1);
			$arr = explode('?', $back);
			if (isset($arr[1])) {
				$params = explode('&', $arr[1]);
				if (isset($params[1])) {
					foreach($params as &$v) {
						$lines = explode('=', $v);
						if ($lines[0] == 't') {
							$v = 't='.$t;
						}
						unset($v);
					}
					$back = $arr[0].'?'.implode('&', $params);
				} else {
					$back .= '&t='.$t;
				}
			} else {
				$back .= '?t='.$t;
			}
			return redirect($back.'?t='.$t);
		}
	}
}