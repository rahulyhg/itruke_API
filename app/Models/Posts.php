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

class Posts extends Model
{
	public $table = 'posts';
	public $timestamps = false;
	public $appends = ['tagsArr', 'navInfo', 'authorInfo', 'nextInfo', 'prevInfo', 'replyCount'];

	function getTagsArrAttribute()
	{
		return Tag::find(json_decode($this->tags));
	}

	function getNavInfoAttribute()
	{
		return Nav::find($this->navId);
	}

	function getAuthorInfoAttribute () {
		return User::find($this->author);
	}

	function getPrevInfoAttribute()
	{
		return DB::table('posts')->where('addTime', '>', $this->addTime)->orderBy('addTime')->select('id','title')->first();
	}

	function getNextInfoAttribute()
	{
		return DB::table('posts')->where('addTime', '<', $this->addTime)->orderBy('addTime','desc')->select('id','title')->first();
	}

	function getReplyCountAttribute() {
		return Reply::where('key', $this->id)->where('pid', 0)->count();
	}
}