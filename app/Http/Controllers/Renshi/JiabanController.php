<?php

namespace App\Http\Controllers\Renshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Admin\User;
use App\Models\Renshi\Renshi_jiaban;
// use App\Models\Renshi\Renshi_employee;
use DB;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\Admin\permissionExport;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class JiabanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

	
    /**
     * 列出applicant页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jiabanApplicant()
    {
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());

		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
		
		$share = compact('config', 'user');
        return view('renshi.jiaban_applicant', $share);
		}
		
    /**
     * 列出todo页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jiabanTodo()
    {
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());

		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
		
		$share = compact('config', 'user');
        return view('renshi.jiaban_todo', $share);
    }
		
    /**
     * 列出archived页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jiabanArchived()
    {
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());

		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
		
		$share = compact('config', 'user');
        return view('renshi.jiaban_archived', $share);
    }

    /**
     * jiaban applicant列表
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jiabanGetsApplicant(Request $request)
    {
		if (! $request->ajax()) return null;

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$uid = $user['uid'];

		$url = request()->url();
		$queryParams = request()->query();
		
		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;
		
		$queryfilter_auditor = $request->input('queryfilter_auditor');
		$queryfilter_created_at = $request->input('queryfilter_created_at');
		$queryfilter_trashed = $request->input('queryfilter_trashed');
// dd($queryfilter_created_at);
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			$result = Renshi_jiaban::select('id', 'uuid', 'id_of_agent', 'uid_of_agent', 'agent', 'department_of_agent', 'id_of_auditor', 'uid_of_auditor', 'auditor', 'department_of_auditor', 'application', 'status', 'reason', 'remark', 'auditing', 'archived', 'created_at', 'updated_at', 'deleted_at')
				->when($queryfilter_auditor, function ($query) use ($queryfilter_auditor) {
					return $query->where('auditor', 'like', '%'.$queryfilter_auditor.'%');
				})
				->when($queryfilter_created_at, function ($query) use ($queryfilter_created_at) {
					return $query->whereBetween('created_at', $queryfilter_created_at);
				})
				->when($queryfilter_trashed, function ($query) use ($queryfilter_trashed) {
					return $query->onlyTrashed();
				})
				->when($uid > 10, function ($query) use ($uid) {
					// if ($uid > 10) {
						return $query->where('uid_of_agent', $uid);
					// }
				})
				// ->where('uid_of_agent', $user['uid'])
				->where('archived', false)
				->limit(1000)
				->orderBy('created_at', 'desc')
				->paginate($perPage, ['*'], 'page', $page);

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}
		// dd($result);
		return $result;
    }

    /**
     * jiaban todo列表
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jiabanGetsTodo(Request $request)
    {
		if (! $request->ajax()) return null;

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$uid = $user['uid'];
		
		$url = request()->url();
		$queryParams = request()->query();
		
		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;
		
		$queryfilter_applicant = $request->input('queryfilter_applicant');
		$queryfilter_created_at = $request->input('queryfilter_created_at');
		$queryfilter_trashed = $request->input('queryfilter_trashed');

		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			$result = Renshi_jiaban::select('id', 'uuid', 'id_of_agent', 'uid_of_agent', 'agent', 'department_of_agent', 'id_of_auditor', 'uid_of_auditor', 'auditor', 'department_of_auditor', 'application', 'status', 'reason', 'remark', 'auditing', 'archived', 'created_at', 'updated_at', 'deleted_at')
				->when($queryfilter_applicant, function ($query) use ($queryfilter_applicant) {
					return $query->where('agent', 'like', '%'.$queryfilter_applicant.'%');
				})
				->when($queryfilter_created_at, function ($query) use ($queryfilter_created_at) {
					return $query->whereBetween('created_at', $queryfilter_created_at);
				})
				->when($queryfilter_trashed, function ($query) use ($queryfilter_trashed) {
					return $query->onlyTrashed();
				})
				->when($uid > 10, function ($query) use ($uid) {
						return $query->where('uid_of_auditor', $uid);
				})
				// ->where('uid_of_auditor', $user['uid'])
				->where('archived', false)
				->limit(1000)
				->orderBy('created_at', 'desc')
				->paginate($perPage, ['*'], 'page', $page);

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}
		// dd($result);
		return $result;
    }


    /**
     * jiaban archived列表
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jiabanGetsArchived(Request $request)
    {
		if (! $request->ajax()) return null;

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$uid = $user['uid'];

		$url = request()->url();
		$queryParams = request()->query();
		
		$perPage = $queryParams['perPage'] ?? 10000;
		$page = $queryParams['page'] ?? 1;
		
		$queryfilter_auditor = $request->input('queryfilter_auditor');
		$queryfilter_created_at = $request->input('queryfilter_created_at');
		$queryfilter_trashed = $request->input('queryfilter_trashed');
// dd($queryfilter_created_at);
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			$result = Renshi_jiaban::select('id', 'uuid', 'id_of_agent', 'uid_of_agent', 'agent', 'department_of_agent', 'id_of_auditor', 'uid_of_auditor', 'auditor', 'department_of_auditor', 'application', 'status', 'reason', 'remark', 'auditing', 'archived', 'created_at', 'updated_at', 'deleted_at')
				->when($queryfilter_auditor, function ($query) use ($queryfilter_auditor) {
					return $query->where('auditor', 'like', '%'.$queryfilter_auditor.'%');
				})
				->when($queryfilter_created_at, function ($query) use ($queryfilter_created_at) {
					return $query->whereBetween('created_at', $queryfilter_created_at);
				})
				->when($queryfilter_trashed, function ($query) use ($queryfilter_trashed) {
					return $query->onlyTrashed();
				})
				->when($uid > 10, function ($query) use ($uid) {
					// if ($uid > 10) {
						return $query->where('uid_of_agent', $uid);
					// }
				})
				// ->where('uid_of_agent', $user['uid'])
				->where('archived', true)
				->limit(1000)
				->orderBy('created_at', 'desc')
				->paginate($perPage, ['*'], 'page', $page);

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}
		// dd($result);
		return $result;
    }

    /**
     * 列出人员uid
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
			// $result = Renshi_employee::when($queryfilter_name, function ($query) use ($queryfilter_name) {
			$result = User::when($queryfilter_name, function ($query) use ($queryfilter_name) {
					return $query->where('uid', 'like', '%'.$queryfilter_name.'%');
				})
				->where('id', '>', 10)
				->limit(10)
				->orderBy('created_at', 'desc')
				->pluck('uid', 'uid')->toArray();

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}

		return $result;
    }

    /**
     * 列出auditingList
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function auditingList(Request $request)
    {
		if (! $request->ajax()) return null;

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		$url = request()->url();
		$queryParams = request()->query();
		
		$id = $request->input('id');
		
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			// $result = Renshi_employee::select('applicant', 'department')
			$result = User::select('auditing')
				->where('id', $id)
				->first();

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}
// dd($result['auditing']);
		return $result['auditing'];
    }

    /**
     * 列出人员信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function employeeList(Request $request)
    {
		if (! $request->ajax()) return null;

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		$url = request()->url();
		$queryParams = request()->query();
		
		$employeeid = $request->input('employeeid');
		
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			// $result = Renshi_employee::select('applicant', 'department')
			$result = User::select('displayname', 'department')
				->when($employeeid, function ($query) use ($employeeid) {
					return $query->where('uid', $employeeid);
				})
				->first();

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}

		return $result;
    }


		/**
     * applicantCreate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applicantCreate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$created_at = date('Y-m-d H:i:s');
		$updated_at = date('Y-m-d H:i:s');

		$reason = $request->input('reason');
		$remark = $request->input('remark');
		$piliangluru = $request->input('piliangluru');


		$uuid4 = Uuid::uuid4();
		$uuid = $uuid4->toString();

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);

		// dd($user['uid']);
		// dd($user['department']);
		// dd($user['displayname']);
		
		$id_of_agent = $user['id'];
		$uid_of_agent = $user['uid'];
		$agent = $user['displayname'];
		$department_of_agent = $user['department'];

		// get auditor
		$a = User::select('auditing')
		->where('id', $user['id'])
		->first();

		$b = json_decode($a['auditing'], true);

		$id_of_auditor = $b[0]['id'];
		$uid_of_auditor = $b[0]['uid'];
		$auditor = $b[0]['name'];
		$department_of_auditor = $b[0]['department'];

		// dd($department_of_auditor);

		foreach ($piliangluru as $key => $value) {
			$s[$key]['uid'] = $value['uid'];
			$s[$key]['applicant'] = $value['applicant'];
			$s[$key]['department'] = $value['department'];
			$s[$key]['category'] = $value['category'];
			$s[$key]['datetimerange'] = date('Y-m-d H:i', strtotime($value['datetimerange'][0])) . ' - ' . date('Y-m-d H:i', strtotime($value['datetimerange'][1]));
			$s[$key]['duration'] = $value['duration'];
		}

		$application = json_encode(
			$s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

// dd($application);
// dd($s);
// dd($user);
		
		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			// foreach ($s as $value) {
				// Bpjg_zhongricheng_main::create($value);
			// }
			// Bpjg_zhongricheng_relation::insert($s);

			Renshi_jiaban::create([
					'uuid' => $uuid,
					'id_of_agent' => $id_of_agent,
					'uid_of_agent' => $uid_of_agent,
					'agent' => $agent,
					'department_of_agent' => $department_of_agent,
					'id_of_auditor' => $id_of_auditor,
					'uid_of_auditor' => $uid_of_auditor,
					'auditor' => $auditor,
					'department_of_auditor' => $department_of_auditor,
					// 'application' => json_encode($s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
					'application' => $application,
					'status' => 1,
					'reason' => $reason,
					'remark' => $remark,
			]);

			$result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			DB::rollBack();
			return 'Message: ' .$e->getMessage();
			return 0;
		}

		DB::commit();
		Cache::flush();
		return $result;		
    }

    /**
     * 软删除applicant
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applicantTrash(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$id = $request->input('id');

		$result = Renshi_jiaban::whereIn('id', $id)->delete();

		return $result;
    }

    /**
     * 硬删除applicant
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applicantDelete(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$id = $request->input('id');

		$result = Renshi_jiaban::where('id', $id)->forceDelete();

		return $result;
    }

    /**
     * 恢复软删除applicant
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applicantRestore(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$id = $request->input('id');

		// $trashed = Renshi_jiaban::select('deleted_at')
		// 	->whereIn('id', $id)
		// 	->first();

		// 如果在回收站里，则恢复它
		// if ($trashed == null) {
			$result = Renshi_jiaban::where('id', $id)->restore();
		// }

		return $result;
		}
		
    /**
     * applicantArchived
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applicantArchived(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$jiaban_id = $request->input('jiaban_id');

		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			// foreach ($s as $value) {
				// Bpjg_zhongricheng_main::create($value);
			// }
			// Bpjg_zhongricheng_relation::insert($s);

			$result = Renshi_jiaban::where('id', $jiaban_id)
				->update([
					'archived' => true,
				]);

			// $result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			DB::rollBack();
			// return 'Message: ' .$e->getMessage();
			return 0;
		}

		DB::commit();
		Cache::flush();
		return $result;	
    }


		/**
     * todoPass
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function todoPass(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$created_at = date('Y-m-d H:i:s');
		$jiaban_id = $request->input('jiaban_id');
		$jiaban_id_of_agent = $request->input('jiaban_id_of_agent');
		$opinion = $request->input('opinion');

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		
		// $id_of_auditor = $user['id'];
		// $uid_of_auditor = $user['uid'];
		$auditor = $user['displayname'];
		$department_of_auditor = $user['department'];

		$auditing_before = Renshi_jiaban::select('status', 'auditing')
			->where('id', $jiaban_id)
			->first();

		$nowtime = date("Y-m-d H:i:s",time());
		$auditing_after = [];
		if ($auditing_before['auditing']) {
			$auditing_after = json_decode($auditing_before['auditing'], true);
		}
		array_push($auditing_after,
			array(
				"auditor" => $auditor,
				"department" => $department_of_auditor,
				"opinion" => $opinion,
				"created_at" => $nowtime
			)
		);

		// dd($auditing_after);

		$auditing =  json_encode(
			$auditing_after, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

		// get agent
		$agent = User::select('auditing')
		->where('id', $jiaban_id_of_agent)
		->first();

		// 代理人相应的审核人的数量
		$agent_auditing = json_decode($agent['auditing'], true);
		$agent_count = count($agent_auditing);

		// 订单的状态数字
		$jiaban_status = $auditing_before['status'];

		if ($jiaban_status >= $agent_count) {
			$id_of_auditor = $user['id'];
			$uid_of_auditor = $user['uid'];
			$auditor = $user['displayname'];
			$department_of_auditor = $user['department'];

			// 状态99为结案
			$jiaban_status = 99;
		} else {
			//获取下一个auditor
			$id_of_auditor = $agent_auditing[$jiaban_status]['id'];
			$uid_of_auditor = $agent_auditing[$jiaban_status]['uid'];
			$auditor = $agent_auditing[$jiaban_status]['name'];
			$department_of_auditor = $agent_auditing[$jiaban_status]['department'];

			$jiaban_status++;
		}


		// dd($agent_auditing);

// dd($application);
// dd($s);
// dd($user);
		
		// 写入数据库
		try	{
			DB::beginTransaction();
			
			// 此处如用insert可以直接参数为二维数组，但不能更新created_at和updated_at字段。
			// foreach ($s as $value) {
				// Bpjg_zhongricheng_main::create($value);
			// }
			// Bpjg_zhongricheng_relation::insert($s);

			$result = Renshi_jiaban::where('id', $jiaban_id)
				->update([
					'id_of_auditor' => $id_of_auditor,
					'uid_of_auditor' => $uid_of_auditor,
					'auditor' => $auditor,
					'department_of_auditor' => $department_of_auditor,
					'auditing' => $auditing,
					'status' => $jiaban_status,
				]);

			// $result = 1;
		}
		catch (\Exception $e) {
			// echo 'Message: ' .$e->getMessage();
			DB::rollBack();
			// return 'Message: ' .$e->getMessage();
			return 0;
		}

		DB::commit();
		Cache::flush();
		return $result;		
    }











    /**
     * 创建permission ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionCreate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
        $permissionname = $request->input('name');

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');
		$permission = Permission::create(['name' => $permissionname]);
		Cache::flush();
        return $permission;
    }

    /**
     * 删除permission ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionDelete(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$permissionid = $request->input('tableselect');

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');
		
		// 判断是否在已被使用之列
		// 1.查出model_has_permissions表中的permission_id
		$model_has_permissions = DB::table('model_has_permissions')
			->select('permission_id as id')->pluck('id')->toArray();
		// $model_has_permissions_tmp = array_column($model_has_roles, 'id');

		// 2.查出role_has_permissions表中的permission_id
		$role_has_permissions = DB::table('role_has_permissions')
			->select('permission_id as id')->pluck('id')->toArray();
		$role_has_permissions_tmp = array_column($role_has_permissions, 'id');

		// 3.合并前删除重复，model_has_permissions和role_has_permissions两个表的结果
		$permission_used = array_merge($model_has_permissions, $role_has_permissions_tmp);
		$permission_used_tmp = array_unique($permission_used);

		// 4.判断是否在列
		// $flag = false;
		// foreach ($permissionid as $value) {
			// if (in_array($value, $permission_used_tmp)) {
				// $flag = true;
				// break;
			// }
		// }
		$flag = array_intersect($permissionid, $permission_used_tmp);
		// dd($flag);
		// 如果在使用之列，则不允许删除
		if ($flag) return false;
		
        // 如没被使用，则可以删除
		$result = Permission::whereIn('id', $permissionid)->delete();
		Cache::flush();
		return $result;
    }
	
	
    /**
     * 更新当前角色的权限
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roleUpdatePermission(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
        $roleid = $request->input('roleid');
        $permissionid = $request->input('permissionid');

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');
		
		// 1.查询role
		$role = Role::where('id', $roleid)->first();

		// 2.查询permission
		$permissions = Permission::whereIn('id', $permissionid)
			->pluck('name')->toArray();

		$result = $role->syncPermissions($permissions);

		// $role = Role::where('id', $roleid)->first();
		// $permission = Permission::whereIn('id', $permissionid)->pluck('name')->toArray();
		// $permissionall = Permission::pluck('name')->toArray();

		// 注意：revokePermissionTo似乎不接受数组
		// foreach ($permissionall as $permissionname) {
			// $result = $role->revokePermissionTo($permissionname);
		// }

		// foreach ($permission as $permissionname) {
			// $result = $role->givePermissionTo($permissionname);
		// }
		Cache::flush();
        return $result;
    }	

    /**
     * 角色赋予permission
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function permissionGive(Request $request)
    // {
		// if (! $request->isMethod('post') || ! $request->ajax()) { return null; }
		
        // $roleid = $request->input('params.roleid');
        // $permissionid = $request->input('params.permissionid');

		// 重置角色和权限的缓存
		// app()['cache']->forget('spatie.permission.cache');

		// $role = Role::where('id', $roleid)->first();
		// $permission = Permission::whereIn('id', $permissionid)->pluck('name')->toArray();
		
		// foreach ($permission as $permissionname) {
			// $result = $role->givePermissionTo($permissionname);
		// }
		
        // return $result;
    // }

    /**
     * 角色移除permission
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function permissionRemove(Request $request)
    // {
		// if (! $request->isMethod('post') || ! $request->ajax()) { return null; }
		
        // $roleid = $request->input('params.roleid');
        // $permissionid = $request->input('params.permissionid');

		// 重置角色和权限的缓存
		// app()['cache']->forget('spatie.permission.cache');

		// $role = Role::where('id', $roleid)->first();
		// $permission = Permission::whereIn('id', $permissionid)->pluck('name')->toArray();

		// 注意：revokePermissionTo似乎不接受数组
		// foreach ($permission as $permissionname) {
			// $result = $role->revokePermissionTo($permissionname);
		// }

        // return $result;
    // }

    /**
     * 列出角色拥有permissions ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roleHasPermission(Request $request)
    {
		if (! $request->ajax()) return null;

		$roleid = $request->input('roleid');

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');
		
		// 获取当前角色拥有的权限
		// $rolehaspermission = DB::table('users')
			// ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
			// ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
			// ->where('users.id', $roleid)
			// ->pluck('roles.name', 'roles.id')->toArray();
		$rolehaspermission = Role::join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
			->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
			->where('roles.id', $roleid)
			// ->pluck('permissions.name', 'permissions.id')->toArray();
			->select('permissions.id')
			->get()->toArray();
		$rolehaspermission = array_column($rolehaspermission, 'id'); //变成一维数组

		// $rolenothaspermission = Permission::select('id', 'name')
			// ->whereNotIn('id', array_keys($rolehaspermission))
			// ->pluck('name', 'id')->toArray();
		
		$allpermissions = Permission::pluck('name', 'id')->toArray();

		// $result['rolehaspermission'] = $rolehaspermission;
		// $result['rolenothaspermission'] = $rolenothaspermission;
		$result = compact('rolehaspermission', 'allpermissions');

		return $result;
    }

    /**
     * 列出所有待删除的权限 ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionListDelete(Request $request)
    {
		if (! $request->ajax()) { return null; }
		
		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		// 1.查出全部permission的id
		// $role = Role::select('id')->get()->toArray();
		// $role_tmp = array_column($role, 'id'); //变成一维数组
		$permission_tmp = Permission::select('id')->pluck('id')->toArray();

		// 2.查出model_has_roles表中的role_id
		$model_has_permissions = DB::table('model_has_permissions')
			->select('permission_id as id')->pluck('id')->toArray();
		// $model_has_roles_tmp = array_column($model_has_permissions, 'id');

		// 3.查出role_has_permissions表中的role_id
		$role_has_permissions = DB::table('role_has_permissions')
			->select('permission_id as id')->pluck('id')->toArray();
		// $role_has_permissions_tmp = array_column($role_has_permissions, 'id');

		// 4.合并前删除重复，model_has_roles和role_has_permissions两个表的结果
		$permission_used = array_merge($model_has_permissions, $role_has_permissions);
		$permission_used_tmp = array_unique($permission_used);

		// 5.排除已被使用的role，剩余的既是没被使用的role的id
		$unused_permission_id = array_diff($permission_tmp, $permission_used_tmp);
		
		// 6.查询没被使用的role
		$result = Permission::whereIn('id', $unused_permission_id)
			->pluck('name', 'id')->toArray();

		return $result;
    }

    /**
     * 列出所有权限 ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionList(Request $request)
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
			$result = Permission::when($queryfilter_name, function ($query) use ($queryfilter_name) {
					return $query->where('name', 'like', '%'.$queryfilter_name.'%');
				})
				->limit(10)
				->orderBy('created_at', 'desc')
				->pluck('name', 'id')->toArray();

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}
		
		return $result;
    }

    /**
     * 根据权限查看哪些角色 ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionToViewRole(Request $request)
    {
		if (! $request->ajax()) return null;
		
		$permissionid = $request->input('permissionid');

		$role = Role::join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
			->where('role_has_permissions.permission_id', $permissionid)
			->pluck('roles.name', 'roles.id')->toArray();

		return $role;
    }

    /**
     * 测试用户是否有权限
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function testUsersPermission(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$userid = $request->input('userid');
		$permissionid[] = $request->input('permissionid');

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		// 1.查询User
		$user = User::where('id', $userid)->first();
		
		// 2.查询Permission
		$permissions = Permission::whereIn('id', $permissionid)
			->pluck('name')->toArray();

		// 3.测试用户是否有权限
		$result = $user->hasAnyPermission($permissions);

		return $result ? 1 : 0;
    }

	
    /**
     * 编辑权限 ajax
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionUpdate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;

		$id = $request->input('id');
		$name = $request->input('name');

		try	{
			$result = Permission::where('id', $id)
				->update([
					'name'	=>	$name,
				]);
		}
		catch (Exception $e) {//捕获异常
			// echo 'Message: ' .$e->getMessage();
			$result = 0;
		}
		Cache::flush();
		return $result;
    }
	
	
	
	// 角色列表Excel文件导出
    public function excelExport()
    {
		// if (! $request->ajax()) { return null; }
		
		// 获取扩展名配置值
		$config = Config::select('cfg_name', 'cfg_value')
			->pluck('cfg_value', 'cfg_name')->toArray();

		$EXPORTS_EXTENSION_TYPE = $config['EXPORTS_EXTENSION_TYPE'];

        // 获取用户信息
		// Excel数据，最好转换成数组，以便传递过去
		
		$permission = Permission::select('id', 'name', 'guard_name', 'created_at', 'updated_at')
			->limit(5000)
			->orderBy('created_at', 'asc')
			->get()->toArray();		

		// Excel标题第一行，可修改为任意名字，包括中文
		$title[] = ['id', 'name', 'guard_name', 'created_at', 'updated_at'];

		// 合并Excel的标题和数据为一个整体
		$data = array_merge($title, $permission);

		return Excel::download(new permissionExport($data), 'permissions'.date('YmdHis',time()).'.'.$EXPORTS_EXTENSION_TYPE);
    }
	
    /**
     * 列出所有角色，用于查看哪些用户正在使用
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roleList(Request $request)
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
			$result = Role::when($queryfilter_name, function ($query) use ($queryfilter_name) {
					return $query->where('name', 'like', '%'.$queryfilter_name.'%');
				})
				->limit(10)
				->orderBy('created_at', 'desc')
				->pluck('name', 'id')->toArray();

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}

		return $result;
    }
	
	
    /**
     * 列出所有用户，用于测试是否有权限
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userList(Request $request)
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
					return $query->where('name', 'like', '%'.$queryfilter_name.'%');
				})
				->limit(10)
				->orderBy('created_at', 'desc')
				->pluck('name', 'id')->toArray();

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}

		return $result;
    }	
	
}
