<?php

use Illuminate\Database\Seeder;

use App\Models\Renshi\Renshi_jiaban;
// use App\Models\Renshi\Renshi_jiaban_sub;
// use App\Models\Renshi\Renshi_jiaban_sub_2_main;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Renshi_jiabansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
		$nowtime = date("Y-m-d H:i:s",time());

        // Renshi_jiaban_main::truncate();
        DB::statement('truncate table renshi_jiabans restart identity cascade');


        // json数据写入，通过 '{"key1": "value1", "key2": "value2"}' 的形式。
        // 数据库最好用jsonb类型。
        // 例子
        // Renshi_jiaban_main::create([
        //     'uuid' => 'MJB000000001',
        //     'agent' => 'zhangsan',
        //     'department' => 'shengchanbu',
        //     'info' => '{
        //         "main_id": "MJB000000002",
        //         "sub_id": "SJB0005",
        //         "applicant": "zhangsan",
        //         "department": "shengchanbu",
        //         "leibie": "shuanxiujiaban",
        //         "kaishi_riqi": "' . $nowtime . '",
        //         "jiesu_riqi": "' . $nowtime . '",
        //         "duration": 60,
        //         "liyou": "liyou2",
        //         "remark": ""
        //     }',
        // ]);

        $uuid4 = Uuid::uuid4();
        Renshi_jiaban::create([
            // 'uuid' => '6ba7b810-9dad-11d1-80b4-00c04fd430c8',
            'uuid' => $uuid4->toString(),
            'agent' => 'user1',
            'department_of_agent' => 'user',
            'auditor' => 'admin',
            'department_of_auditor' => 'admin',
            'application' => json_encode(
                array(
                    array(
                        "uid" => "0003",
                        "applicant" => "user1",
                        'department' => 'caiwu',
                        'category' => 'pingshijiaban',
                        'datetimerange' => $nowtime . ' - ' . $nowtime,
                        'duration' => 60,
                    ),
                    array(
                        "uid" => "0004",
                        "applicant" => "user2",
                        'department' => '生产计划管理部',
                        'category' => '节假日加班补',
                        'datetimerange' => $nowtime . ' - ' . $nowtime,
                        'duration' => 60,
                    )
                ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            // 'applicant' => 'lisi',
            // 'department_of_applicant' => 'caiwu',
            // 'category' => 'pingshijiaban',
            // 'start_date' => $nowtime,
            // 'end_date' => $nowtime,
            // 'duration' => 60,
            'status' => 1,
            'reason' => 'reason1',
            'remark' => '',
            'auditing' => json_encode(
                array(
                    array(
                        "auditor" => "admin",
                        "opinion" => "balabala1...."
                    ),
                    array(
                        "auditor" => "admin",
                        "opinion" => "balabala2...."
                    )
                ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
        ]);

        $uuid4 = Uuid::uuid4();
        Renshi_jiaban::create([
            'uuid' => $uuid4->toString(),
            'agent' => 'user2',
            'department_of_agent' => 'user',
            'auditor' => 'admin',
            'department_of_auditor' => 'admin',
            'application' => json_encode(
                array(
                    array(
                        "uid" => "071015516",
                        "applicant" => "王五",
                        'department' => 'caiwu',
                        'category' => 'pingshijiaban',
                        'datetimerange' => $nowtime . ' - ' . $nowtime,
                        'duration' => 30,
                    ),
                ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'status' => 1,
            'reason' => 'reason2',
            'remark' => '',
            'auditing' => json_encode(
                array(
                    array(
                        "auditor" => "admin",
                        "opinion" => "balabala1...."
                    )
                ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
        ]);

        $uuid4 = Uuid::uuid4();
        Renshi_jiaban::create([
            'uuid' => $uuid4->toString(),
            'agent' => 'user3',
            'department_of_agent' => 'user',
            'auditor' => 'admin',
            'department_of_auditor' => 'admin',
            'application' => json_encode(
                array(
                    array(
                        "uid" => "071111111",
                        "applicant" => "赵六",
                        'department' => 'caiwu',
                        'category' => 'pingshijiaban',
                        'datetimerange' => $nowtime . ' - ' . $nowtime,
                        'duration' => 30,
                    ),
                ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'status' => 1,
            'reason' => 'reason3',
            'remark' => '',
            'auditing' => json_encode(
                array(
                    array(
                        "auditor" => "admin",
                        "opinion" => "balabala1...."
                    )
                ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
        ]);

        // Renshi_jiaban_sub::create([
        //     'applicant' => 'lisi',
        //     'department' => 'caiwu',
        //     'category' => 'pingshijiaban',
        //     'start_date' => $nowtime,
        //     'end_date' => $nowtime,
        //     'duration' => 30,
        //     'reason' => 'reason1',
        //     'remark' => '',
        // ]);

        // Renshi_jiaban_sub::create([
        //     'applicant' => 'wangwu',
        //     'department' => 'caiwu',
        //     'category' => 'pingshijiaban',
        //     'start_date' => $nowtime,
        //     'end_date' => $nowtime,
        //     'duration' => 60,
        //     'reason' => 'reason2',
        //     'remark' => '',
        // ]);

		// Renshi_jiaban_sub_2_main::insert(array (
        //     0 => 
        //     array (
        //         'main_id' => '1',
        //         'sub_id' => '1',
        //         ),
        //     1 => 
        //     array (
        //         'main_id' => '1',
        //         'sub_id' => '2',
        //         ),
        // ));		



        
        
	
		
    }
}
