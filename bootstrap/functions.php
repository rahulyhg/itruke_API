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
	 * @param string $ctr
	 * @param string midle
	 *
	 * TODO add a controller route
	 */
	function add_route ($ctr, ...$midle){
		$ctr = ucfirst($ctr);
		$app = app();
		$methods = get_class_methods('App\Http\Controllers\\'.$ctr.'Controller');
		if ($methods) {
			$app->group([
				'middleware' => array_merge(['api'],$midle)
			],function () use ($app, $methods, $ctr) {
				foreach($methods as $v) {
					$actions = ['get', 'post', 'put', 'delete'];
					foreach($actions as $a) {
						if (strpos($v, $a) === 0) {
							$app->options(strtolower($ctr).'/'.snake_case(ltrim($v, $a)), function () {
								return response('', 200);
							});
							break;
						}
					}
					foreach($actions as $a) {
						if (strpos($v, $a) === 0) {
							$app->$a(strtolower($ctr).'/'.snake_case(ltrim($v, $a)), $ctr.'Controller@'.camel_case($a.'_'.ltrim($v, $a)));
						}
					}
				}
			});
		}
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
