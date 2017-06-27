<?php
/**
 * Created by LEYA.
 * User: 陈老二
 * Email: chenJiao@163.com
 * Date: 2017/6/27
 * Time: 14:13
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
	public $table = 'nav';
	public $timestamps = false;

	static function fetchList () {
		$list = self::where('pid', 0)->get();
		foreach($list as $l) {
			$l->sub = self::subNav($l->id);
		}
		return $list;
	}

	static function subNav ($id) {
		$list = self::where('pid', $id)->get();
		if (count($list)) {
			foreach ($list as $l) {
				$l->sub = self::subNav($l->id);
			}
			return $list;
		}
		return [];
	}
}