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

        Renshi_jiaban::create([
            'uuid' => '6ba7b810-9dad-11d1-80b4-00c04fd430c8',
            'agent' => 'zhangsan',
            'department_of_agent' => 'shengchanbu',
            'applicant' => 'lisi',
            'department_of_applicant' => 'caiwu',
            'category' => 'pingshijiaban',
            'start_date' => $nowtime,
            'end_date' => $nowtime,
            'duration' => 60,
            'status' => 1,
            'reason' => 'reason1',
            'remark' => '',
            'auditing' => json_encode(
                array(
                    array(
                        "auditor" => "领导1",
                        "opinion" => "balabala1...."
                    ),
                    array(
                        "auditor" => "领导2",
                        "opinion" => "balabala2...."
                    )
                ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
            ),
        ]);

        Renshi_jiaban::create([
            'uuid' => '6ba7b810-9dad-11d1-80b4-00c04fd430c9',
            'agent' => 'zhangsan',
            'department_of_agent' => 'shengchanbu',
            'applicant' => 'wangwu',
            'department_of_applicant' => 'caiwu',
            'category' => 'pingshijiaban',
            'start_date' => $nowtime,
            'end_date' => $nowtime,
            'duration' => 30,
            'status' => 1,
            'reason' => 'reason2',
            'remark' => '',
            // 'auditing' => ['{
            //     "auditor": "lingdao1",
            //     "opinion": "balabala1...."
            // }'],
        ]);

        Renshi_jiaban::create([
            'uuid' => '6ba7b810-9dad-11d1-80b4-00c04fd430c0',
            'agent' => 'zhangsan',
            'department_of_agent' => 'shengchanbu',
            'applicant' => 'zhaoliu',
            'department_of_applicant' => 'caiwu',
            'category' => 'pingshijiaban',
            'start_date' => $nowtime,
            'end_date' => $nowtime,
            'duration' => 120,
            'status' => 1,
            'reason' => 'reason3',
            'remark' => '',
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
