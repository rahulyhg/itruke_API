<?php
/**
 * Created by LEYA.
 * User: 陈老二
 * Email: chenJiao@163.com
 * Date: 2017/7/17
 * Time: 15:41
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	public $table = 'replys';
	public $timestamps = false;
	public $appends = ['userInfo', 'address'];

	static function fetchList ($key) {
		$list = self::where('pid', 0)->where('key', $key)->orderBy('addTime')->get();
		foreach($list as $l) {
			$l->sub = self::subNav($l->id, $key);
		}
		return $list;
	}

	static function subNav ($id, $key) {
		$list = self::where('pid', $id)->where('key', $key)->orderBy('addTime')->get();
		if (count($list)) {
			foreach ($list as $l) {
				$l->sub = self::subNav($l->id, $key);
			}
			return $list;
		}
		return [];
	}

	function getUserInfoAttribute()
	{
		return User::find($this->userId);
	}

	function getAddressAttribute() {
		if ($this->ip === '127.0.0.1') {
			return '技术宅男子';
		}
		$res = http_get('http://ip.taobao.com/service/getIpInfo.php?ip='.$this->ip);
		$res = json_decode($res);
		return $res->data->region.' '.$res->data->city;
	}
}