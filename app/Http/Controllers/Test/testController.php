<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Adldap\AdldapInterface;
use Adldap\Laravel\Facades\Adldap;

use DB;
use Image;

class testController extends Controller
{
    // test界面
	public function test() {

		return view('test.test');
		
	}
	
	
	// phpinfo
	public function phpinfo() {

		return view('test.phpinfo');
		
	}


	// ldap
	public function ldap(AdldapInterface $ldap)
    {

		// dd($ldap->search()->users()->get());
		$data = $ldap->search()->users()->get()->toArray();
// dd($data);

		$username = 'ca071215958';
		$password = 'Aota12345678';

		try {
			$ldap = Adldap::auth()->attempt(
				// $user['name'] . env('LDAP_ACCOUNT_SUFFIX'),
				$username,
				$password
				);
				
			// 获取用户email
			$user_tmp = Adldap::search()->users()->find($username);		
			$email = $user_tmp['mail'][0];
		}
		// catch (Exception $e) {
		catch (\Adldap\Auth\BindException $e) { //捕获异常
			echo 'Message: ' .$e->getMessage();
			$ldap = false;
		}
dd($email);

		// return view('test.ldap', [
            // 'users' => $ldap->search()->users()->get()
        // ]);
		return view('test.ldap', $data);
    }
	
	
    // scroll界面
	public function scroll() {

		return view('test.scroll');
		
	}

    // mint界面
	public function mint() {

		return view('test.mint');
		
	}

    // muse界面
	public function muse() {

		return view('test.muse');
		
	}

    // vant界面
	public function vant() {

		return view('test.vant');
		
	}

    // cube界面
	public function cube() {

		return view('test.cube');
		
	}

    // pgsql界面
	public function pgsql()
    {

		$res['data1'] = null;
		$res['data2'] = null;
		$res['data3'] = null;

		$res['data1'] = DB::connection('mysql')->table('users')->find(1);
		$res['data2'] = DB::connection('pgsql')->table('users')->find(1);
		$res['data3'] = DB::connection('sqlsrv')->table('PerEmployee')->first();

		return view('test.pgsql', $res);
    }	

    // 测试camera
	public function camera()
    {

		$res['data1'] = null;

		$res['data1'] = DB::connection('pgsql')->table('renshi_jiabans')
		->select('camera_imgurl')
		->where('id', 2)
		->first();

		return view('test.camera', $res);
    }	



	/**
	 * testCamera
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function testCamera(Request $request)
	{
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$data = $request->only(
			'id',
			'imgurl'
		);

		$mydate = date('y-m-d H:i:s',time());
		$myuser = '年年庆余年';

		$img = Image::make($data['imgurl'])
			->resize(160, 120)
			->text($myuser, 20, 20, function($font) {
				$font->file(public_path() . '/fonts/msyh.ttc');
				$font->size(9);
				$font->color('#fdf6e3');
				$font->align('center');
				$font->valign('middle');
				$font->angle(45);
			})
			->text($mydate, 80, 110, function($font) {
				$font->file(public_path() . '/fonts/msyh.ttc');
				$font->size(9);
				$font->color('#fdf6e3');
				$font->align('center');
				$font->valign('middle');
				// $font->angle(45);
			})
			->encode('png')
			->encode('data-url');
		
		// dd($img);
		// dd($img->encoded);
		// dd($data);
		$data['imgurl'] = $img->encoded;

		// 写入数据库
		try	{
			$result = DB::table('renshi_jiabans')
			->where('id', $data['id'])
				->update([
					'camera_imgurl'	=> $data['imgurl'],
				]);
			$result = 1;
		}
		catch (\Exception $e) {
			echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
	}



}
