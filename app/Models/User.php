<?php
/**
 * Created by LEYA.
 * User: 陈老二
 * Email: chenJiao@163.com
 * Date: 2017/6/29
 * Time: 18:40
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
	public $timestamps = false;
	public $table = 'users';

	public $appends = ['wxCode'];

	function getWxCodeAttribute()
	{
		if ($this->wxopenid)
			return DB::table('wxuser')->where('openid', $this->wxopenid)->value('code');
		return null;
	}
}