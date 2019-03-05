<?php

use Illuminate\Database\Seeder;

use App\Models\Renshi\Jiaban;

class JiabansTableSeeder extends Seeder
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
        
        Jiaban::create([
            'main_id' => 'MJB000000001',
            'agent' => 'zhangsan',
            'department' => 'shengchanbu',
            'info' => json_encode([
                [
                'main_id' => 'MJB000000001',
                'sub_id' => 'SJB0002',
                'applicant' => 'zhangsan',
                'department' => 'shengchanbu',
                'leibie' => 'jiaban1',
                'kaishi_riqi' => $nowtime,
                'jiesu_riqi' => $nowtime,
                'duration' => 60,
                'liyou' => 'liyou1111',
                'remark' => 'remark1'
                ],
                [
                'main_id' => 'MJB000000001',
                'sub_id' => 'SJB0003',
                'applicant' => 'zhangsan',
                'department' => 'shengchanbu',
                'leibie' => 'jiaban2',
                'kaishi_riqi' => $nowtime,
                'jiesu_riqi' => $nowtime,
                'duration' => 60,
                'liyou' => 'liyou2222',
                'remark' => ''
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK),
        ]);
        
        Jiaban::create([
            'main_id' => 'MJB000000002',
            'agent' => 'lisi',
            'department' => 'caiwubu',
            'info' => json_encode([
                [
                'main_id' => 'MJB000000002',
                'sub_id' => 'SJB0005',
                'applicant' => 'zhangsan',
                'department' => 'shengchanbu',
                'leibie' => 'shuanxiujiaban',
                'kaishi_riqi' => $nowtime,
                'jiesu_riqi' => $nowtime,
                'duration' => 60,
                'liyou' => 'liyou2',
                'remark' => ''
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK),
        ]);
        
	
		
    }
}
