<?php

use Illuminate\Database\Seeder;

use App\Models\Renshi\Jiaban;

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
		
        Jiaban::truncate();

        // json数据写入，通过 '{"key1": "value1", "key2": "value2"}' 的形式。
        // 数据库最好用jsonb类型。
        Jiaban::create([
            'main_id' => 'MJB000000001',
            'agent' => 'zhangsan',
            'department' => 'shengchanbu',
            'info' => '{
                "main_id": "MJB000000002",
                "sub_id": "SJB0005",
                "applicant": "zhangsan",
                "department": "shengchanbu",
                "leibie": "shuanxiujiaban",
                "kaishi_riqi": "' . $nowtime . '",
                "jiesu_riqi": "' . $nowtime . '",
                "duration": 60,
                "liyou": "liyou2",
                "remark": ""
            }',
        ]);
        
        // Jiaban::create([
        //     'main_id' => 'MJB000000002',
        //     'agent' => 'lisi',
        //     'department' => 'caiwubu',
        //     'info' => json_encode([
        //         '{
        //         "main_id" => "MJB000000002",
        //         "sub_id" => "SJB0005",
        //         "applicant" => "zhangsan",
        //         "department" => "shengchanbu",
        //         "leibie" => "shuanxiujiaban",
        //         "kaishi_riqi" => $nowtime,
        //         "jiesu_riqi" => $nowtime,
        //         "duration" => 60,
        //         "liyou" => "liyou2",
        //         "remark" => ""
        //         }'
        //     ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK),
        // ]);
        
	
		
    }
}
