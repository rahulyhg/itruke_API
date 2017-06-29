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

class Posts extends Model
{
	public $table = 'posts';
	public $timestamps = false;
	public $appends = ['tagsArr'];

	function getTagsArrAttribute()
	{
		return Tag::find(json_decode($this->tags));
	}
}