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
            'main_id' => 'JBM000000001',
            'applicant' => '张三',
            'department' => '生产部',
            'info' => json_encode([
                [
                'leibie' => '平时加班1',
                'kaishi_riqi' => $nowtime,
                'jiesu_riqi' => $nowtime,
                'liyou' => '理由1111'
                ],
                [
                'leibie' => '平时加班2',
                'kaishi_riqi' => $nowtime,
                'jiesu_riqi' => $nowtime,
                'liyou' => '理由2222'
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK),
        ]);
        
        Jiaban::create([
            'main_id' => 'JBM000000002',
            'applicant' => '李四',
            'department' => '财务部',
            'info' => json_encode([
                [
                'leibie' => '双休加班',
                'kaishi_riqi' => $nowtime,
                'jiesu_riqi' => $nowtime,
                'liyou' => '理由2'
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK),
        ]);
        
	
		
    }
}
