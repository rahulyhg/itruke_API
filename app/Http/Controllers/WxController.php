<?php
/**
 * Created by LEYA.
 * User: 陈老二
 * Email: chenJiao@163.com
 * Date: 2017/10/15
 * Time: 16:36
 */
namespace App\Http\Controllers;
use App\Models\Posts;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WxController extends Controller
{
	function serve (Request $request) {
		$wx = app('wechat');
		$wx->server->setMessageHandler(function ($message) {
			//need to send a msg to server 告知有用户扫码
			if ($message->MsgType == 'event' && $message->Event == 'subscribe') {
				$openid = $message->FromUserName;
				$code = self::addOpenid($openid);
				return '技术宅男子欢迎您,您的微信码是'.$code;
			}
			return null;
		});
		return $wx->server->serve();
	}

	static function addOpenid ($openid) {
		$has = DB::table('wxuser')->where('openid', $openid)->first();
		if ($has) {
			return $has->code;
		} else {
			$last = DB::table('wxuser')->orderBy('id')->first();
			if ($last) {
				$id = $last->id + 1;
			} else {
				$id = 1;
			}
			$code = str_random(1);
			$code .= $id;
			$code .= str_random(6-strlen($code));
			DB::table('wxUser')->insert(['openid'=>$openid, 'code'=>$code]);
			return $code;
		}
	}

	static function sendMessage ($uid, $rid) {
		$app = app('wechat');
		$reply = Reply::find($rid);
		if($reply->pid == 0) {
			$user = User::find(1);
		} else {
			$preply = Reply::where('id', $reply->pid)->first();
			$user = User::find($preply->userId);
		}
		if (!$user->wxopenid) {
			return;
		}
		if ($reply->key == 0) {
			$title = '留言板';
			$url = 'https://itruke.com/guest';
		} else {
			$po = Posts::find($reply->key);
			$title = $po->title;
			$url = 'https://itruke.com/info/'.$po->id;
		}
		if ($reply->pid == 0) {
			$remark = '顶级评论';
		} else {
			$remark = '回复你的评论: '.$preply->content;
		}
		$notice = $app->notice;
		$notice->send([
			'touser' => $user->wxopenid,
			'url' => $url,
			'template_id' => 'IVlwMHLQAwUnV1dwTeu2lr8nIz5EKq9bq079-6GIkh8',
			'data' => [
				'title' => $title,
				'user' => $user->name,
				'content' => $reply->content,
				'uri' => $url,
				'time' => $reply->addTime,
				'remark' => $remark
			]
		]);
	}
}