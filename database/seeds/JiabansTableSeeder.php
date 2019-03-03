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
		
		Jiaban::insert(array (
            0 => 
            array (
                'gonghao' => '071215958',
                'xingming' => 'zhangsan',
                'xinxi' => json_encode("{
                    'leibie': '平时加班',
                    'kaishi_riqi': $nowtime,
                    'jiesu_riqi': $nowtime,
                    'liyou': '我愿意'
                }"),
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            1 => 
            array (
                'gonghao' => '081215958',
                'xingming' => 'lisi',
                'xinxi' => json_encode("{
                    'leibie': '平时加班',
                    'kaishi_riqi': $nowtime,
                    'jiesu_riqi': $nowtime,
                    'liyou': '我愿意'
                }"),
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
            ),
            2 => 
            array (
                'gonghao' => '091215958',
                'xingming' => 'wangwu',
                'xinxi' => json_encode("{
                    'leibie': '平时加班',
                    'kaishi_riqi': $nowtime,
                    'jiesu_riqi': $nowtime,
                    'liyou': '我愿意'
                }"),
				'created_at' => $nowtime,
                'updated_at' => $nowtime,

            ),
        ));		
		
    }
}
