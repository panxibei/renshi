<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Admin\User;
use Cookie;
use Validator;
use Adldap\Laravel\Facades\Adldap;

class LogincubeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		if (! sizeof($user)) {
			// 无有效用户登录，则认证失败，退回登录界面
		} else {
			// 如果是已经登录，则跳转至门户页面
			return redirect()->route('portalcube');
		}
        $config = Config::pluck('cfg_value', 'cfg_name')->toArray();
        return view('home.login_cube', $config);
	}

	public function checklogin(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$name = $request->input('name');
		$password = $request->input('password');

		// 判断单用户登录
		$singleUser = User::select('login_time', 'login_ttl')->where('name', $name)->first();
		$user_login_time = strtotime($singleUser['login_time']);
		$user_login_ttl = $singleUser['login_ttl'] * 60;
		$user_login_expire = $user_login_time + $user_login_ttl;
		$user_now = time();
		
		if ($user_now < $user_login_expire) {
			// return $user_login_time . '|' . $user_login_ttl . '|' .$user_now . 'singleuser';
			return 'nosingleuser';
		}


		// $minutes = 480;
		// $minutes = config('jwt.ttl', 60);
		// $minutes = $rememberme ? config('jwt.ttl', 60*24*365) : config('jwt.jwt_cookies_ttl', 60*24);
		$minutes = config('jwt.jwt_cookies_ttl', 60*24);

		// 5.jwt-auth，判断用户认证
		$credentials = $request->only('name', 'password');

		$token = auth()->attempt($credentials);
		if (! $token) {
			// 如果认证失败，则返回null
			// return response()->json(['error' => 'Unauthorized'], 401);
			return null;
		}
		
		
		try	{
			$nowtime = date("Y-m-d H:i:s",time());
				
			$result = User::where('name', $name)
				->increment('login_counts', 1, [
					'login_time' => $nowtime,
					'login_ttl' => $minutes,
					'login_ip'   => $_SERVER['REMOTE_ADDR'],
				]);
		}
		catch (Exception $e) {//捕获异常
			// echo 'Message: ' .$e->getMessage();
			// dd($e->getMessage());
			$result = null;
		}

		// return $this->respondWithToken($token);
		Cookie::queue('token', $token, $minutes);
		// dd($token);
		return $token;
		
  }

}
