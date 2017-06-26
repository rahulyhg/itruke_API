<?php
/**
 * Created by LEYA.
 * User: 陈老二
 * Email: chenJiao@163.com
 * Date: 2017/6/26
 * Time: 15:10
 */
namespace App\Http\Controllers;

class AdminController extends Controller
{
	function getIndex () {
		return success('admin/inedex');
	}
}