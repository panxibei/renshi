<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Admin\User;
use App\Models\Renshi\Renshi_jiaban;
use DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\Admin\userExport;
use Illuminate\Support\Facades\Cache;


// use Illuminate\Database\Eloquent\Collection;
// use Illuminate\Support\Collection;

class UserController extends Controller
{

	// public function __construct(\Maatwebsite\Excel\Exporter $excel)
	// {
		// $this->excel = $excel;
	// }


    /**
     * 列出用户页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userIndex()
    {
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());
		
		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
        // return view('admin.user', $config);
		
		$share = compact('config', 'user');
        return view('admin.user', $share);
    }
	

    /**
     * 列出用户页面 ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userList(Request $request)
    {
		if (! $request->ajax()) return null;
		
		$url = request()->url();
		$queryParams = request()->query();
		
		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;

        // 获取用户信息
		// $perPage = $request->input('perPage');
		// $page = $request->input('page');
		
		$queryfilter_name = $request->input('queryfilter_name');
		$queryfilter_logintime = $request->input('queryfilter_logintime');
		$queryfilter_email = $request->input('queryfilter_email');
		$queryfilter_loginip = $request->input('queryfilter_loginip');

		$user = User::select('id', 'uid', 'name', 'department', 'auditing', 'ldapname', 'email', 'displayname', 'login_time', 'login_ip', 'login_counts', 'created_at', 'updated_at', 'deleted_at')
			->when($queryfilter_logintime, function ($query) use ($queryfilter_logintime) {
				return $query->whereBetween('login_time', $queryfilter_logintime);
			})
			->when($queryfilter_name, function ($query) use ($queryfilter_name) {
				return $query->where('name', 'like', '%'.$queryfilter_name.'%');
			})
			->when($queryfilter_email, function ($query) use ($queryfilter_email) {
				return $query->where('email', 'like', '%'.$queryfilter_email.'%');
			})
			->when($queryfilter_loginip, function ($query) use ($queryfilter_loginip) {
				return $query->where('login_ip', 'like', '%'.$queryfilter_loginip.'%');
			})
			->limit(1000)
			->orderBy('id', 'desc')
			->withTrashed()
			->paginate($perPage, ['*'], 'page', $page);

		return $user;
    }

    /**
     * 创建用户 ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userCreate(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax()) return false;

		// $newuser = $request->only('name', 'email');
		// $nowtime = date("Y-m-d H:i:s",time());
		$name = $request->input('name');
		$department = $request->input('department');
		$uid = $request->input('uid');
		$email = $request->input('email');
		$displayname = $request->input('displayname');
		$password = $request->input('password');
		
		$logintime = date("Y-m-d H:i:s", 86400);
		
		$result = User::create([
			'name'     		=> $name,
			'department'  	=> $department,
			'uid'			=> $uid,
			'email'			=> $email,
			'displayname'	=> $displayname,
			'password' 		=> bcrypt($password),
			'login_time' 	=> $logintime,
			'login_ip' 		=> '255.255.255.255',
			'login_counts' 	=> 0,
			'remember_token'=> '',
			// 'created_at' => $nowtime,
			// 'updated_at' => $nowtime,
			// 'deleted_at' => NULL
		]);

		return $result;
    }

    /**
     * 禁用用户（软删除） ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userTrash(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$userid = $request->input('userid');

		// 如果是管理员id为1，则不能删除
		if ($userid == 1) return false;
		
		$usertrashed = User::select('deleted_at')
			->where('id', $userid)
			->first();

		// 如果在回收站里，则恢复它
		if ($usertrashed == null) {
			$result = User::where('id', $userid)->restore();
		} else {
			$result = User::where('id', $userid)->delete();
		}

		return $result;
    }

    /**
     * 删除用户 ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userDelete(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax()) return false;
		
		$userid = $request->input('tableselect');
		
		// 判断两个表（model_has_permissions和model_has_roles）中，
		// 是否已有用户被分配了角色或权限
		// 如果已经分配了，则不允许删除
		$model_has_permissions = DB::table('model_has_permissions')
			->whereIn('model_id', $userid)
			->first();
		// dd($model_has_permissions);

		$model_has_roles = DB::table('model_has_roles')
			->whereIn('model_id', $userid)
			->first();
		// dd($model_has_roles);
		
		if ($model_has_permissions != null || $model_has_roles != null) {
			return 0;
		}

		try	{
			$result = User::whereIn('id', $userid)->forceDelete();
			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		
		// Cache::flush();
		return $result;
		
    }

    /**
     * 编辑用户 ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userUpdate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');
		$name = $request->input('name');
		$email = $request->input('email');
		$displayname = $request->input('displayname');
		$department = $request->input('department');
		$uid = $request->input('uid');
		$password = $request->input('password');
		// $created_at = $request->input('created_at');
		// $updated_at = $request->input('updated_at');

		try	{
			// 如果password为空，则不更新密码
			if (isset($password)) {
				$result = User::where('id', $id)
					->update([
						'name'			=> $name,
						'department'	=> $department,
						'uid'			=> $uid,
						'email' 		=> $email,
						'displayname' 	=> $displayname,
						'password'		=> bcrypt($password)
					]);
			} else {
				$result = User::where('id', $id)
					->update([
						'name'			=> $name,
						'department'	=> $department,
						'uid'			=> $uid,
						'email' 		=> $email,
						'displayname' 	=> $displayname
					]);
			}
		}
		catch (Exception $e) {//捕获异常
			// echo 'Message: ' .$e->getMessage();
			// return 'Message: ' .$e->getMessage();
			$result = 0;
		}
		
		return $result;
    }




	// 用户列表Excel文件导出
    public function excelExport()
    {
		
		// if (! $request->ajax()) { return null; }
		
		// 获取扩展名配置值
		$config = Config::select('cfg_name', 'cfg_value')
			->pluck('cfg_value', 'cfg_name')->toArray();

		$EXPORTS_EXTENSION_TYPE = $config['EXPORTS_EXTENSION_TYPE'];
		$FILTERS_USER_NAME = $config['FILTERS_USER_NAME'];
		$FILTERS_USER_EMAIL = $config['FILTERS_USER_EMAIL'];
		$FILTERS_USER_LOGINTIME = $config['FILTERS_USER_LOGINTIME'];
		$FILTERS_USER_LOGINIP = $config['FILTERS_USER_LOGINIP'];

        // 获取用户信息
		// Excel数据，最好转换成数组，以便传递过去
		$queryfilter_name = $FILTERS_USER_NAME ?: '';
		$queryfilter_email = $FILTERS_USER_EMAIL ?: '';
		$queryfilter_logintime = $FILTERS_USER_LOGINTIME ?: ['1970-01-01', '9999-12-31'];
		$queryfilter_loginip = $FILTERS_USER_LOGINIP ?: '';
		
		$user = User::select('id', 'name', 'ldapname', 'email', 'displayname', 'login_time', 'login_ip', 'login_counts', 'created_at', 'updated_at', 'deleted_at')
			->when($queryfilter_logintime, function ($query) use ($queryfilter_logintime) {
				return $query->whereBetween('login_time', $queryfilter_logintime);
			})
			->when($queryfilter_name, function ($query) use ($queryfilter_name) {
				return $query->where('name', 'like', '%'.$queryfilter_name.'%');
			})
			->when($queryfilter_email, function ($query) use ($queryfilter_email) {
				return $query->where('email', 'like', '%'.$queryfilter_email.'%');
			})
			->when($queryfilter_loginip, function ($query) use ($queryfilter_loginip) {
				return $query->where('login_ip', 'like', '%'.$queryfilter_loginip.'%');
			})
			->limit(5000)
			->orderBy('created_at', 'asc')
			->withTrashed()
			->get()->toArray();		

        // 示例数据，不能直接使用，只能把数组变成Exports类导出后才有数据
		// $cellData = [
            // ['学号','姓名','成绩'],
            // ['10001','AAAAA','199'],
            // ['10002','BBBBB','192'],
            // ['10003','CCCCC','195'],
            // ['10004','DDDDD','189'],
            // ['10005','EEEEE','196'],
        // ];

		// Excel标题第一行，可修改为任意名字，包括中文
		$title[] = ['id', 'name', 'ldapname', 'email', 'displayname', 'login_time', 'login_ip', 'login_counts', 'created_at', 'updated_at', 'deleted_at'];

		// 合并Excel的标题和数据为一个整体
		$data = array_merge($title, $user);

		// dd(Excel::download($user, '学生成绩', 'Xlsx'));
		// dd(Excel::download($user, '学生成绩.xlsx'));
		return Excel::download(new userExport($data), 'users'.date('YmdHis',time()).'.'.$EXPORTS_EXTENSION_TYPE);
		
    }



    /**
     * 清除用户TTL
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userClsttl(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');

		try	{
			$result = User::where('id', $id)
				->update([
					'login_ttl'	=>	0,
				]);
			// $result = 1;
		}
		catch (Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		// dd($result);
		return $result;
		}
		

    /**
     * 列出用户 ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uidList(Request $request)
    {
		if (! $request->ajax()) return null;
		
		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		$url = request()->url();
		$queryParams = request()->query();
		
		$queryfilter_name = $request->input('queryfilter_name');

		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");

		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			$result = User::when($queryfilter_name, function ($query) use ($queryfilter_name) {
					return $query->where('uid', 'like', '%'.$queryfilter_name.'%');
				})
				->where('id', '>', 10)
				->limit(10)
				->orderBy('created_at', 'desc')
				->pluck('uid', 'id')->toArray();

			Cache::put($fullUrl, $result, now()->addSeconds(60));
		}
		
		return $result;
    }


    /**
     * 列出用户所指向的auditing信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userHasAuditing1(Request $request)
    {
		if (! $request->ajax()) return null;

		$applicant = $request->input('applicant');
		
		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');
		
		// 获取当前用户所指向的auditing
		$userhasauditing = User::select('uid', 'displayname', 'auditing')
			->where('id', $applicant)
			->first();

		$uid = $userhasauditing['uid'];
		$username = $userhasauditing['displayname'];
		// $auditing = json_decode($userhasauditing['auditing'], true);
		$auditing = $userhasauditing['auditing'];
		
		// $allusers = User::pluck('name', 'id')->toArray();

		$result = compact('uid', 'username', 'auditing');

		return $result;
    }

    /**
     * 列出用户所指向的auditing信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userHasAuditing2(Request $request)
    {
		if (! $request->ajax()) return null;

		$userid = $request->input('userid');
		
		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');
		
		// 获取当前用户所指向的auditing
		$userhasauditing = User::select('uid', 'displayname', 'auditing')
			->where('id', $userid)
			->first();

		$uid = $userhasauditing['uid'];
		$username = $userhasauditing['displayname'];
		// $auditing = json_decode($userhasauditing['auditing'], true);
		$auditing = $userhasauditing['auditing'];
		
		// $allusers = User::pluck('name', 'id')->toArray();

		$result = compact('uid', 'username', 'auditing');

		return $result;
    }

    /**
     * auditingUpdate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function auditingUpdate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');
		
		$id_current = $request->input('applicant');
		$id_auditings = $request->input('auditings');

		$user_auditing = User::select('id', 'uid', 'displayname as name', 'department')
			->whereIn('id', $id_auditings)
			->get()->toArray();
		
		$auditing_after = json_encode(
			$user_auditing
			, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

		try	{
			$result = User::where('id', $id_current)
				->update([
					// 'auditing' => json_encode(
					// 	$auditing_after
					// , JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
					// )
					'auditing' => $auditing_after
				]);
			// $result = 1;

			// 获取当前用户所指向的auditing
			$userhasauditing = User::select('auditing')
			->where('id', $id_current)
			->first();

			// $result = json_decode($userhasauditing['auditing'], true);
			$result = $userhasauditing['auditing'];

		}
		catch (Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}

		return $result;
		}

    /**
     * auditingAdd
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function auditingAdd(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');
		
		$id_current = $request->input('id_current');
		$id_auditing = $request->input('id_auditing');

		// if ($id_current == $id_auditing) return 0;

		$user_auditing = User::select('id', 'uid', 'displayname as name', 'department')
			->where('id', $id_auditing)
			->first()->toArray();
		
		$user_current = User::select('auditing')
			->where('id', $id_current)
			->first()->toArray();

		if ($user_current['auditing'] != null) {
			// $auditing_after = json_decode($user_current['auditing'], true);
			$auditing_after = $user_current['auditing'];
			array_push($auditing_after, $user_auditing);
		} else {
			$auditing_after[] = $user_auditing;
		}

		// dd($auditing_after);
		$auditing_after = json_encode(
			$auditing_after
			, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);


		try	{
			$result = User::where('id', $id_current)
				->update([
					// 'auditing' => json_encode(
					// 	$auditing_after
					// , JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
					// )
					'auditing' => $auditing_after
				]);
			// $result = 1;

			// 获取当前用户所指向的auditing
			$userhasauditing = User::select('auditing')
			->where('id', $id_current)
			->first();

			// $result = json_decode($userhasauditing['auditing'], true);
			$result = $userhasauditing['auditing'];

		}
		catch (Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		// dd($result);
		return $result;
	}

    /**
     * auditingSort
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function auditingSort(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$index = $request->input('index');
		$id = $request->input('id');
		$uid = $request->input('uid');
		$sort = $request->input('sort');
		// dd($index);

		$user = User::select('auditing')
			->where('id', $id)
			->first();
		// dd(json_decode($user['auditing']));

		// $auditing_before = json_decode($user['auditing'], true);
		$auditing_before = $user['auditing'];
// dd($auditing_before);
		$auditing_after = [];

		if ('down' == $sort) {
			foreach ($auditing_before as $key => $value) {
				if ($key != $index && $key != $index+1) {
					array_push($auditing_after, $value);
				}
				elseif ($key == $index) {
					$auditing_after[$key] = $auditing_before[$index+1];
				}
				elseif ($key == $index+1) {
					$auditing_after[$key] = $auditing_before[$index];
				}
			}
		} elseif ('up' == $sort) {
			foreach ($auditing_before as $key => $value) {
				if ($key != $index && $key != $index-1) {
					array_push($auditing_after, $value);
				}
				elseif ($key == $index) {
					$auditing_after[$key] = $auditing_before[$index-1];
				}
				elseif ($key == $index-1) {
					$auditing_after[$key] = $auditing_before[$index];
				}
			}
		}

		// dd($auditing_after);
		// if ($auditing_after == null) {
			$auditing_after_json = json_encode(
				$auditing_after
				, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			);
		// }

		try	{
			DB::beginTransaction();

			//1.写入调整后的顺序
			User::where('id', $id)
				->update([
					// 'auditing' => json_encode(
					// 	$auditing_after
					// , JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
					// )
					'auditing' => $auditing_after_json
				]);

			//2.修正流程
			$index_of_auditor = Renshi_jiaban::select('index_of_auditor')
				->where('id_of_agent', $id)
				->get()->toArray();
			// dd($index_of_auditor);

			foreach ($index_of_auditor as $key=>$value) {
				if ($value['index_of_auditor'] != null) {
					$index_tmp = $value['index_of_auditor'];

					Renshi_jiaban::where('id_of_agent', $id)
						->where('index_of_auditor', $index_tmp)
						->update([
							'id_of_auditor' => $auditing_after[$index_tmp-1]['id'],
							'uid_of_auditor' => $auditing_after[$index_tmp-1]['uid'],
							'auditor' => $auditing_after[$index_tmp-1]['name'],
							'department_of_auditor' => $auditing_after[$index_tmp-1]['department'],
						]);
				}
			}

			//3.获取当前用户所指向的auditing，用于刷新
			$userhasauditing = User::select('auditing')
			->where('id', $id)
			->first();

			// $result = json_decode($userhasauditing['auditing'], true);
			$result = $userhasauditing['auditing'];

		}
		catch (Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			DB::rollBack();
			$result = 0;
		}

		DB::commit();
		Cache::flush();
		// dd($result);
		return $result;
    }

    /**
     * auditingRemove
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function auditingRemove(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');
		
		$index = $request->input('index');
		$id = $request->input('id');
		$uid = $request->input('uid');

		$user = User::select('auditing')
			->where('id', $id)
			->first();

		// dd(json_decode($user['auditing']));

		// $auditing_before = json_decode($user['auditing'], true);
		$auditing_before = $user['auditing'];
// dd($auditing_before);
		$auditing_after = [];
		foreach ($auditing_before as $key => $value) {
			// if ($value['uid'] != $uid) {
			// 	array_push($auditing_after, $value);
			// }
			if ($key != $index) {
				array_push($auditing_after, $value);
			}
		}

		// dd($auditing_after);
		// if ($auditing_after == null) {
			$auditing_after = json_encode(
				$auditing_after
				, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			);
		// }

		try	{
			$result = User::where('id', $id)
				->update([
					// 'auditing' => json_encode(
					// 	$auditing_after
					// , JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
					// )
					'auditing' => $auditing_after
				]);
			// $result = 1;
// dd($result);
			// 获取当前用户所指向的auditing
			$userhasauditing = User::select('auditing')
			->where('id', $id)
			->first();

			// $result = json_decode($userhasauditing['auditing'], true);
			$result = $userhasauditing['auditing'];

		}
		catch (Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		// dd($result);
		return $result;
		}



    /**
     * 加载外部数据源用户
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getExternalUsers(Request $request)
    {
		if (! $request->ajax()) return null;
		
		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		$externalusers = DB::connection('sqlsrv')
			->select('SELECT EmpNo as uid, EmpNo as name, EmpName as displayname, PartName as department FROM kaoqin.dbo.PerEmployee');

		dd($externalusers);



		$url = request()->url();
		$queryParams = request()->query();
		
		$queryfilter_name = $request->input('queryfilter_name');

		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");

		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			$result = User::when($queryfilter_name, function ($query) use ($queryfilter_name) {
					return $query->where('uid', 'like', '%'.$queryfilter_name.'%');
				})
				->where('id', '>', 10)
				->limit(10)
				->orderBy('created_at', 'desc')
				->pluck('uid', 'id')->toArray();

			Cache::put($fullUrl, $result, now()->addSeconds(60));
		}
		
		return $result;
    }












}
