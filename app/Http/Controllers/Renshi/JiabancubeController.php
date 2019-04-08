<?php

namespace App\Http\Controllers\Renshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Config;
use App\Models\Admin\User;
use App\Models\Renshi\Renshi_jiaban;
use DB;

use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;


class JiabancubeController extends Controller
{
    /**
     * 列出applicant页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jiabancubeApplicant()
    {
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());

		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
		
		$share = compact('config', 'user');
		return view('renshi.jiaban_cube_applicant', $share);
		}


    /**
     * 列出applicant list 页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jiabancubeApplicantList()
    {
		// 获取JSON格式的jwt-auth用户响应
		$me = response()->json(auth()->user());

		// 获取JSON格式的jwt-auth用户信息（$me->getContent()），就是$me的data部分
		$user = json_decode($me->getContent(), true);
		// 用户信息：$user['id']、$user['name'] 等

        // 获取配置值
		$config = Config::pluck('cfg_value', 'cfg_name')->toArray();
		
		$share = compact('config', 'user');
		return view('renshi.jiaban_cube_applicant_list', $share);
		}

    

	/**
     * applicantcubeCreate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applicantcubeCreate(Request $request)
    {
		if (! $request->isMethod('post') || ! $request->ajax()) return null;
        
		$uid = $request->input('uid');
		$applicant = $request->input('applicant');
		$department = $request->input('department');
		$startdate = $request->input('startdate');
		$enddate = $request->input('enddate');
		$duration = $request->input('duration');
		$category = $request->input('category');
		$reason = $request->input('reason');
		$remark = $request->input('remark');
		// $piliangluru = $request->input('piliangluru');


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

		// foreach ($piliangluru as $key => $value) {
			$s[0]['uid'] = $uid;
			$s[0]['applicant'] = $applicant;
			$s[0]['department'] = $department;
			$s[0]['category'] = $category;
			$s[0]['datetimerange'] = $startdate . ' - ' . $enddate;
			$s[0]['duration'] = $duration;
		// }

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
     * jiaban cube applicant列表
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jiabancubeGetsApplicant(Request $request)
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
		
		$perPage = $queryParams['perPage'] ?? 10;
		$page = $queryParams['page'] ?? 1;
		
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
				// ->when($queryfilter_auditor, function ($query) use ($queryfilter_auditor) {
				// 	return $query->where('auditor', 'like', '%'.$queryfilter_auditor.'%');
				// })
				// ->when($queryfilter_created_at, function ($query) use ($queryfilter_created_at) {
				// 	return $query->whereBetween('created_at', $queryfilter_created_at);
				// })
				// ->when($queryfilter_trashed, function ($query) use ($queryfilter_trashed) {
				// 	return $query->onlyTrashed();
				// })
				// ->when($uid > 10, function ($query) use ($uid) {
				// 	// if ($uid > 10) {
				// 		return $query->where('uid_of_agent', $uid);
				// 	// }
				// })
				// ->where('uid_of_agent', $user['uid'])
				->where('archived', false)
				// ->where('uid_of_agent', '>', 10)
				// ->onlyTrashed()
				// ->offset(10)
				->limit(10)
				->orderBy('created_at', 'desc')
				->paginate($perPage, ['*'], 'page', $page);

			Cache::put($fullUrl, $result, now()->addSeconds(1));
		}
		// dd($result);
		return $result;
    }






}
