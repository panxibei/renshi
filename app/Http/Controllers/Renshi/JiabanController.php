<?php

namespace App\Http\Controllers\Renshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Admin\User;
use App\Models\Renshi\Renshi_jiaban;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Renshi\jiaban_applicantExport;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class JiabanController extends Controller
{
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

    // 获取系统配置
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

    // 获取系统配置
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

    // 获取系统配置
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

		if (empty($employeeid)) return null;
		
		//对查询参数按照键名排序
		ksort($queryParams);

		//将查询数组转换为查询字符串
		$queryString = http_build_query($queryParams);

		$fullUrl = sha1("{$url}?{$queryString}");
		
		//首先查寻cache如果找到
		if (Cache::has($fullUrl)) {
			$result = Cache::get($fullUrl);    //直接读取cache
		} else {                                   //如果cache里面没有
			$result = User::select('displayname', 'department')
				->when($employeeid, function ($query) use ($employeeid) {
					return $query->where('uid', $employeeid);
				})
				->where('id', '>', 10)
				->first();

			Cache::put($fullUrl, $result, now()->addSeconds(10));
		}

		return $result;
    }

	

    /**
     * loadApplicant
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function loadApplicant(Request $request)
    {
		if (! $request->ajax()) return null;

		$node = $request->input('node');
		$title = $request->input('title');
		
		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		// 公司总node
		if ($node != 'department') {
			$res = User::select('department')
				->where('department', '<>', 'admin')
				// ->where('department', '<>', 'user')
				->distinct()
				->get()->toArray();
			
			$result = [];
			foreach ($res as $value) {
				array_push($result, $value['department']); 
				// $result[$value['department']] = $value['department']; 
			}

		} else {
			// 部门node
			$res = User::select('id', 'displayname')
			->where('department', $title)
				->get()->toArray();

				$result = [];
				foreach ($res as $value) {
					array_push($result, $value['displayname'] . ' (ID:' . $value['id'] . ')'); 
					// $result[$value['department']] = $value['department']; 
				}
				// dd($result);
		}

		return $result;
    }


		/**
     * createApplicantGroup
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createApplicantGroup(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$title = $request->input('title');
		$applicants = $request->input('applicants');

		$ag['title'] = $title;
		$ag['applicants'] = $applicants;

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$userid = $user['id'];

		// 查询已有applicant_group信息
		$t = User::select('applicant_group')
			->where('id', $userid)
			->first();
		// dd(json_decode($t['applicant_group'], true));
		
		if ($t['applicant_group']) {

			$applicant_group = json_decode($t['applicant_group'], true);
			// dd($applicant_group);

			// $after_applicant_group = $before_applicant_group;
			array_push($applicant_group, $ag);
			// dd($applicant_group);
		} else {
			$applicant_group[] = $ag;
		}

		$applicant_group = json_encode(
			$applicant_group, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

		// dd($applicant_group);

		// 写入数据库
		try	{
			DB::beginTransaction();
			
			$result = User::where('id', $userid)
			->update([
				'applicant_group' => $applicant_group,
			]);

			$result = 1;
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
     * deleteApplicantGroup
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteApplicantGroup(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		$title = $request->input('title');

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$userid = $user['id'];

		// 查询已有applicant_group信息
		$t = User::select('applicant_group')
			->where('id', $userid)
			->first();
		// dd(json_decode($t['applicant_group'], true));
		
		if ($t['applicant_group']) {

			$applicant_group = json_decode($t['applicant_group'], true);

			$applicant_group_result = [];
			foreach ($applicant_group as $key => $value) {
				if ($value['title'] != $title) {
					array_push($applicant_group_result, $value);
				}
			}
		} else {
			return 0;
		}

		// dd($applicant_group_result);

		$applicant_group_result = json_encode(
			$applicant_group_result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

		// dd($applicant_group_result);

		// 写入数据库
		try	{
			DB::beginTransaction();
			
			$result = User::where('id', $userid)
			->update([
				'applicant_group' => $applicant_group_result,
			]);

			$result = 1;
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
     * loadApplicantGroup
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function loadApplicantGroup(Request $request)
    {
		if (! $request->ajax()) return null;

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$userid = $user['id'];
		
		$res = User::select('applicant_group')
			->where('id', $userid)
			->first();
		// dd($res['applicant_group']);

		$result = json_decode($res['applicant_group'], true);
		// dd($result);

		return $result;
		}
		

    /**
     * loadApplicantGroupDetails
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function loadApplicantGroupDetails(Request $request)
    {
		if (! $request->ajax()) return null;

		$applicantgroup = $request->input('applicantgroup');

		// 重置角色和权限的缓存
		app()['cache']->forget('spatie.permission.cache');

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);
		$userid = $user['id'];
		
		$res1 = User::select('applicant_group')
			->where('id', $userid)
			->first();
		// dd($res['applicant_group']);

		$res2 = json_decode($res1['applicant_group'], true);

		foreach ($res2 as $key => $value) {
			if ($applicantgroup == $value['title']) {
				$result = $value['applicants'];
				break;
			}
		}
		// dd($result);

		return $result;
    }


		/**
     * applicantCreate1
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applicantCreate1(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		// $created_at = date('Y-m-d H:i:s');
		// $updated_at = date('Y-m-d H:i:s');

		$reason = $request->input('reason');
		$remark = $request->input('remark');
		$category = $request->input('category');
		$duration = $request->input('duration');
		$datetimerange = $request->input('datetimerange');
		$applicantgroup = $request->input('applicantgroup');

		$uuid4 = Uuid::uuid4();
		$uuid = $uuid4->toString();

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);

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

		// 查找批量applicant信息
		$res1 = User::select('applicant_group')
			->where('id', $id_of_agent)
			->first();

		$res2 = json_decode($res1['applicant_group'], true);

		foreach ($res2 as $key => $value) {
			if ($applicantgroup == $value['title']) {
				$res3 = $value['applicants'];
				break;
			}
		}

		foreach ($res3 as $key => $value) {
			$tmpstr = explode(' (ID:', $value);
			$applicant_id[] = substr($tmpstr[1], 0, strlen($tmpstr[1]) - 1);
		}
		// dd($applicant_id);

		// get applicant info
		$users = User::select('uid', 'displayname as applicant', 'department')
			->whereIn('id', $applicant_id)
			->get()->toArray();

		foreach ($users as $key => $value) {
			$s[$key]['uid'] = $value['uid'];
			$s[$key]['applicant'] = $value['applicant'];
			$s[$key]['department'] = $value['department'];
			$s[$key]['category'] = $category;
			$s[$key]['datetimerange'] = date('Y-m-d H:i', strtotime($datetimerange[0])) . ' - ' . date('Y-m-d H:i', strtotime($datetimerange[1]));
			$s[$key]['duration'] = $duration;
		}

		$application = json_encode(
			$s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

		// 写入数据库
		try	{
			DB::beginTransaction();
			
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
			// return 'Message: ' .$e->getMessage();
			return 0;
		}

		DB::commit();
		Cache::flush();
		return $result;		
    }

		/**
     * applicantCreate2
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applicantCreate2(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
		
		// $created_at = date('Y-m-d H:i:s');
		// $updated_at = date('Y-m-d H:i:s');

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
			// return 'Message: ' .$e->getMessage();
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
		Cache::flush();
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
		Cache::flush();
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
		Cache::flush();
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

		$flag = Renshi_jiaban::select('archived')
			->where('id', $jiaban_id)
			->first();


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
					'archived' => ! $flag['archived'],
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
				"status" => 1,
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
			// $id_of_auditor = $user['id'];
			// $uid_of_auditor = $user['uid'];
			// $auditor = $user['displayname'];
			// $department_of_auditor = $user['department'];
			$id_of_auditor = '无';
			$uid_of_auditor = '无';
			$auditor = '无';
			$department_of_auditor = '无';

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
     * todoDeny
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function todoDeny(Request $request)
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
				"status" => 0,
				"opinion" => $opinion,
				"created_at" => $nowtime
			)
		);

		// dd($auditing_after);

		$auditing =  json_encode(
			$auditing_after, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

		// get agent
		$agent = User::select('id', 'uid', 'displayname', 'department', 'auditing')
		->where('id', $jiaban_id_of_agent)
		->first();

		// 代理人相应的审核人的数量
		$agent_auditing = json_decode($agent['auditing'], true);
		$agent_count = count($agent_auditing);
		// dd($agent_auditing);

		// 订单的状态数字
		$jiaban_status = $auditing_before['status'];

		// 第一种，返回到上一级
		// 获取上一个auditor
		// if ($jiaban_status <= 1) {
		// 	// 如果是第一个审核人，则退回到申请人处
		// 	$id_of_auditor = $agent['id'];
		// 	$uid_of_auditor = $agent['uid'];
		// 	$auditor = $agent['displayname'];
		// 	$department_of_auditor = $agent['department'];

		// } else {
		// 	// 否则退回到上一个auditor
		// 	$jiaban_status--;
		// 	$id_of_auditor = $agent_auditing[$jiaban_status-1]['id'];
		// 	$uid_of_auditor = $agent_auditing[$jiaban_status-1]['uid'];
		// 	$auditor = $agent_auditing[$jiaban_status-1]['name'];
		// 	$department_of_auditor = $agent_auditing[$jiaban_status-1]['department'];
		// }

		// 第二种，直接结束
			$jiaban_status = 0;
			$id_of_auditor = '无';
			$uid_of_auditor = '无';
			$auditor = '无';
			$department_of_auditor = '无';

		// dd($agent_auditing[$jiaban_status]);

		
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
			dd('Message: ' .$e->getMessage());
			return 0;
		}

		DB::commit();
		Cache::flush();
		return $result;		
    }



    /**
     * 软删除todo
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function todoTrash(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$id = $request->input('id');

		$result = Renshi_jiaban::whereIn('id', $id)->delete();
		Cache::flush();
		return $result;
    }

    /**
     * 硬删除todo
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function todoDelete(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$id = $request->input('id');

		$result = Renshi_jiaban::where('id', $id)->forceDelete();
		Cache::flush();
		return $result;
    }

    /**
     * 恢复软删除todo
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function todoRestore(Request $request)
    {

		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$id = $request->input('id');

		// 如果在回收站里，则恢复它
		$result = Renshi_jiaban::where('id', $id)->restore();
		Cache::flush();
		return $result;
		}



    /**
     * 软删除archived
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archivedTrash(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$id = $request->input('id');

		$result = Renshi_jiaban::whereIn('id', $id)->delete();
		Cache::flush();
		return $result;
    }

    /**
     * 硬删除archived
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archivedDelete(Request $request)
    {
        //
		if (! $request->isMethod('post') || ! $request->ajax())  return false;

		$id = $request->input('id');

		$result = Renshi_jiaban::where('id', $id)->forceDelete();
		Cache::flush();
		return $result;
    }

    /**
     * 恢复软删除archived
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archivedRestore(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return false;

		$id = $request->input('id');

		// 如果在回收站里，则恢复它
		$result = Renshi_jiaban::where('id', $id)->restore();
		Cache::flush();
		return $result;
		}


    /**
     * 修改用户配置
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeConfigs(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return false;

		$field = $request->input('field');
		$value = $request->input('value');

		// 用户信息：$user['id']、$user['name'] 等
		$me = response()->json(auth()->user());
		$user = json_decode($me->getContent(), true);

		// 先读取配置，后修改配置，最后保存
		$res = User::find($user['id']);
		$configs = $res->configs;
		$configs[$field] = $value;
		$res->configs = $configs;

		try	{
			DB::beginTransaction();
			$res->save();
			$result = 1;
		}
		catch (\Exception $e) {
			DB::rollBack();
			// dd('Message: ' .$e->getMessage());
			return 0;
		}

		DB::commit();
		Cache::flush();
		return $result;
    }








	
	
	
	// 列表Excel文件导出
    public function applicantExport()
    {

		// $jiaban_applicant = Renshi_jiaban::select('id', 'uuid', 'id_of_agent', 'uid_of_agent', 'agent', 'department_of_agent', 'id_of_auditor', 'uid_of_auditor', 'auditor', 'department_of_auditor', 'application', 'status', 'reason', 'remark', 'auditing', 'archived', 'created_at', 'updated_at', 'deleted_at')
		$jiaban_applicant = Renshi_jiaban::select('id', 'uuid', 'id_of_agent', 'uid_of_agent', 'agent', 'department_of_agent', 'id_of_auditor', 'uid_of_auditor', 'auditor', 'department_of_auditor', 'application', 'status', 'reason', 'remark', 'auditing', 'archived', 'created_at', 'updated_at', 'deleted_at')
			// ->when($queryfilter_created_at, function ($query) use ($queryfilter_created_at) {
			// 	return $query->whereBetween('created_at', $queryfilter_created_at);
			// })
			->where('archived', false)
			->limit(5000)
			->orderBy('created_at', 'desc')
			->get()->toArray();
		// dd($jiaban_applicant);		
		// dd($jiaban_applicant[0]['application']);
		
		$s = [];
		$t = [];
		$i = 1;
		foreach ($jiaban_applicant as $key => $value) {
			foreach ($value['application'] as $k => $v) {
				$s[$key][$k]['id'] = $i++;
				$s[$key][$k]['uuid'] = $value['uuid'];

				$s[$key][$k]['uid'] = $v['uid'];
				$s[$key][$k]['applicant'] = $v['applicant'];
				$s[$key][$k]['department'] = $v['department'];
				$s[$key][$k]['category'] = $v['category'];
				$s[$key][$k]['datetimerange'] = $v['datetimerange'];
				$s[$key][$k]['duration'] = $v['duration'];

				$s[$key][$k]['id_of_agent'] = $value['id_of_agent'];
				$s[$key][$k]['uid_of_agent'] = $value['uid_of_agent'];
				$s[$key][$k]['agent'] = $value['agent'];
				$s[$key][$k]['department_of_agent'] = $value['department_of_agent'];
				$s[$key][$k]['id_of_auditor'] = $value['id_of_auditor'];
				$s[$key][$k]['uid_of_auditor'] = $value['uid_of_auditor'];
				$s[$key][$k]['auditor'] = $value['auditor'];
				$s[$key][$k]['department_of_auditor'] = $value['department_of_auditor'];
				if ($value['status']==99) {
					$s[$key][$k]['status'] = '已结案';
				} else if ($value['status']==0) {
					$s[$key][$k]['status'] = '已否决';
				} else {
					$s[$key][$k]['status'] = '处理中';
				}
				$s[$key][$k]['reason'] = $value['reason'];
				$s[$key][$k]['remark'] = $value['remark'];
				$s[$key][$k]['archived'] = $value['archived'];
				$s[$key][$k]['created_at'] = $value['created_at'];
				$s[$key][$k]['updated_at'] = $value['updated_at'];

				$t[] = $s[$key][$k];

			}
		}
		// dd($t);

		// Excel标题第一行，可修改为任意名字，包括中文
		$title[] = ['id', 'uuid', 'uid', 'applicant', 'department', 'category', 'datetimerange', 'duration',
			'id_of_agent', 'uid_of_agent', 'agent', 'department_of_agent',
			'id_of_auditor', 'uid_of_auditor', 'auditor', 'department_of_auditor', 'status', 'reason',
			'remark', 'archived', 'created_at', 'updated_at'];
// dd($title);
		// 合并Excel的标题和数据为一个整体
		$data = array_merge($title, $t);
// dd($data);
		return Excel::download(new jiaban_applicantExport($data), 'jiaban_applicant'.date('YmdHis',time()).'.xlsx');
    }
	

	
	




	
}
