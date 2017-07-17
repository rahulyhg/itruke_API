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


if (! function_exists('http_post')) {
	/**
	 * @param $params
	 *  -url 请求的uri
	 *  -fileds 参数kk
	 *  -header 设置的header
	 * @param int $timeout
	 * @return mixed
	 * TODO 模拟post请求
	 */
	function http_post($params,$timeout=300) {
		$url = @$params['url'];
		$fields = @$params['fields'];
		$header = @$params['header'];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		if(!empty($header)) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		}
		if(!empty($fields)) {
			curl_setopt($curl,CURLOPT_POSTFIELDS,$fields);
		}
		$data = curl_exec($curl);
		@curl_close($curl);
		return $data;
	}
}

if (! function_exists('http_get')) {
	/**
	 * @param $url uri
	 * @param int $timeout
	 * @return mixed
	 * TODO 模拟get请求
	 */
	function http_get($url,$timeout=300) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
}
