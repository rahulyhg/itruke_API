<?php
/**
 * Created by LEYA.
 * User: 陈老二
 * Email: chenJiao@163.com
 * Date: 2017/6/26
 * Time: 14:10
 */

/**  functions of own  **/

if (! function_exists('addRoute')) {
	/**
	 * @param $app
	 * @param null $name
	 * @param string $ctr
	 *
	 * TODO add a controller route
	 */
	function addRoute ($app,$name = null,$ctr = 'Data'){
		if (is_null($name) || $name == 'index') {
			if ($ctr == 'Data') {
				$path = '/';
			} else {
				$path = $ctr;
			}
			$name = 'index';
		} else {
			if ($ctr == 'Data') {
				$path = $name;
			} else {
				$path = $ctr.'/'.$name;
			}
		}
		$app->get($path, $ctr.'Controller@'.camel_case('get_'.$name));
		$app->post($path, $ctr.'Controller@'.camel_case('post_'.$name));
		$app->put($path, $ctr.'Controller@'.camel_case('put_'.$name));
		$app->delete($path, $ctr.'Controller@'.camel_case('delete_'.$name));
	}
}


if (! function_exists('success'))
{
	/**
	 * @param array $data
	 * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
	 *
	 * TODO return a success response
	 */
	function success ($data = [])
	{
		return response()->json([
			'error' => '',
			'result'=> $data
		], 200);
	}
}

if (! function_exists('error'))
{
	/**
	 * @param string $msg
	 * @param int $code
	 * @param array $data
	 * @return \Illuminate\Http\JsonResponse
	 *
	 * TODO return a error response
	 */
	function error ($msg = 'request error', $code = 500, $data = [])
	{
		return response()->json([
			'error' => $msg,
			'result'=> $data
		], $code);
	}
}
