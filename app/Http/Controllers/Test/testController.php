<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Adldap\AdldapInterface;
use Adldap\Laravel\Facades\Adldap;

use DB;

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

		$res['data1'] = DB::connection('mysql')->table('users')->find(1);
		$res['data2'] = DB::connection('pgsql')->table('table1')->find(1);
		$res['data3'] = DB::connection('sqlsrv')->table('um4l_user')->first();

		return view('test.pgsql', $res);
    }	

}
