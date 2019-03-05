<?php

use Illuminate\Database\Seeder;

use App\Models\Renshi\Renshi_jiaban_main;
use App\Models\Renshi\Renshi_jiaban_sub;
use Illuminate\Support\Facades\DB;

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
        DB::statement('truncate table renshi_jiaban_mains restart identity cascade');
        DB::statement('truncate table renshi_jiaban_subs restart identity cascade');


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

        Renshi_jiaban_main::create([
            'uuid' => 'MJB000000002',
            'agent' => 'zhangsan',
            'department' => 'shengchanbu',
        ]);


        Renshi_jiaban_sub::create([
            'applicant' => 'lisi',
            'department' => 'caiwu',
            'category' => 'pingshijiaban',
            'start_date' => $nowtime,
            'end_date' => $nowtime,
            'duration' => 30,
            'reason' => 'reason1',
            'remark' => '',
        ]);

        
        
	
		
    }
}
