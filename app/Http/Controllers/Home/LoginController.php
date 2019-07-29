<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Admin\User;
use Cookie;
use Validator;
use Adldap\Laravel\Facades\Adldap;

class LoginController extends Controller
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
			return redirect()->route('portal');
		}
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
		return view('home.login', $config);
	}

	public function checklogin(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		// $name = $request->input('name');
		// $password = $request->input('password');
		// $captcha = $request->input('captcha');
		$rememberme = $request->input('rememberme');
		
		// 1.判断验证码
		$rules = ['captcha' => 'required|captcha'];
		// $validator = Validator::make(Input::all(), $rules);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			// echo '<p style="color: #ff0000;">Incorrect!</p>';
			// dd('<p style="color: #ff0000;">Incorrect!</p>');
			return null;
		} else {
			// echo '<p style="color: #00ff30;">Matched :)</p>';
			// dd('<p style="color: #00ff30;">Matched :)</p>');
		}

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
		$minutes = $rememberme ? config('jwt.ttl', 60*24*365) : config('jwt.jwt_cookies_ttl', 60*24);

		// 2.adldap判断AD认证
		$adldap = false;
		if (config('ldap.ldap_use_ldap') == 'ldap') {

			try {
				$adldap = Adldap::auth()->attempt(
					// $user['name'] . env('ADLDAP_ADMIN_ACCOUNT_SUFFIX'),
					$name,
					$password
				);
			}
			// catch (Exception $e) {
			catch (\Adldap\Auth\BindException $e) { //捕获异常
				// echo 'Message: ' .$e->getMessage();
				$adldap = false;
			}

			// 3.如果adldap认证成功，则同步本地用户的密码
			//   否则认证失败再由jwt-auth本地判断
			if ($adldap) {
				
				// 获取用户email
				$user_tmp = Adldap::search()->users()->find($name);
				$email = $user_tmp['mail'][0];
				$displayname = $user_tmp['displayname'][0];
				$ldapname = $name;

				// 同步本地用户密码
				$nowtime = date("Y-m-d H:i:s",time());
				try	{
					$result = User::where('name', $name)
						->increment('login_counts', 1, [
							'password'   => bcrypt($password),
							'ldapname'   => $ldapname,
							'email'      => $email,
							'displayname'=> $displayname,
							'login_time' => $nowtime,
							'login_ttl'	 => $minutes,
							'login_ip'   => $_SERVER['REMOTE_ADDR'],
						]);

					// 4.如果没有这个用户，则自动新增用户
					if ($result == 0) {
						$result = User::create([
							'name'          => $name,
							'ldapname'   	=> $ldapname,
							'email'         => $email,
							'displayname'   => $displayname,
							'password'      => bcrypt($password),
							'login_time'    => $nowtime,
							'login_ttl'	 	=> $minutes,
							'login_ip'      => $_SERVER['REMOTE_ADDR'],
							'login_counts'  => 1,
							'remember_token'=> '',
							'created_at'    => $nowtime,
							'updated_at'    => $nowtime,
							'deleted_at'    => NULL
						]);
					}
				}
				catch (Exception $e) {//捕获异常
					// echo 'Message: ' .$e->getMessage();
					// $result = $e->getMessage();
					$result = null;
				}

			} else {
				// 注意：adldap认证失败再由jwt-auth本地判断，不返回失败
				// return null;
			}
		}

		// 5.jwt-auth，判断用户认证
		$credentials = $request->only('name', 'password');

		$token = auth()->attempt($credentials);
		if (! $token) {
			// 如果认证失败，则返回null
			// return response()->json(['error' => 'Unauthorized'], 401);
			return null;
		}
		
		
		// 如果没有经过ldap, 则更新本地用户信息
		if (! $adldap) {
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
				// $result = $e->getMessage();
				$result = null;
			}
		}

		// return $this->respondWithToken($token);
		Cookie::queue('token', $token, $minutes);
		return $token;
		
  }

}
