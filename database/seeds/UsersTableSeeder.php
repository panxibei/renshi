<?php

use Illuminate\Database\Seeder;

use App\Models\Admin\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$nowtime = date("Y-m-d H:i:s",time());
		$logintime = date("Y-m-d H:i:s", 86400);
		
        //
		User::truncate();
		
		// DB::table('configs')->insert(array (
		User::insert(array (
            0 => 
            array (
                // 'id' => 1,
                'uid' => '0001',
                'name' => 'admin',
                'department' => 'admin',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                        array(
                            "id" => "2",
                            "uid" => "0002",
                            "name" => "root",
                            "department" => "root"
                        )
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'admin',
                'email' => 'admin@aota.local',
                'displayname' => 'admin',
                'password' => '$2y$10$LZyZUTTyHugBeHGiSCumi.KKb4doF5eQYoKqIBYR03J84LLcEVVZW', // 默认密码123
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                // 'id' => 2,
                'uid' => '0002',
                'name' => 'root',
                'department' => 'root',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'root',
                'email' => 'root@aota.local',
                'displayname' => 'root',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                // 'id' => 3,
                'uid' => '0003',
                'name' => 'user1',
                'department' => 'user',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'user1',
                'email' => 'user1@aota.local',
                'displayname' => 'user1',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                // 'id' => 4,
                'uid' => '0004',
                'name' => 'user2',
                'department' => 'user',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'user2',
                'email' => 'user2@aota.local',
                'displayname' => 'user2',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                // 'id' => 5,
                'uid' => '0005',
                'name' => 'user3',
                'department' => 'user',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'user3',
                'email' => 'user3@aota.local',
                'displayname' => 'user3',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                // 'id' => 6,
                'uid' => '0006',
                'name' => 'user4',
                'department' => 'user',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'user4',
                'email' => 'user4@aota.local',
                'displayname' => 'user4',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                // 'id' => 7,
                'uid' => '0007',
                'name' => 'user5',
                'department' => 'user',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'user5',
                'email' => 'user5@aota.local',
                'displayname' => 'user5',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                // 'id' => 8,
                'uid' => '0008',
                'name' => 'user6',
                'department' => 'user',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'user6',
                'email' => 'user6@aota.local',
                'displayname' => 'user6',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                // 'id' => 9,
                'uid' => '0009',
                'name' => 'user7',
                'department' => 'user',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'user7',
                'email' => 'user7@aota.local',
                'displayname' => 'user7',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                // 'id' => 10,
                'uid' => '0010',
                'name' => 'user8',
                'department' => 'user',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'user8',
                'email' => 'user8@aota.local',
                'displayname' => 'user8',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                // 'id' => 11,
                'uid' => '071215958',
                'name' => '071215958',
                'department' => 'abc',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'zhang san',
                'email' => 'user8@aota.local',
                'displayname' => 'zhang san',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                // 'id' => 12,
                'uid' => '071015516',
                'name' => '071015516',
                'department' => 'gggg',
                'auditing' => json_encode(
                    array(
                        array(
                            "id" => "1",
                            "uid" => "0001",
                            "name" => "admin",
                            "department" => "admin"
                        ),
                    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ),
                'ldapname' => 'li si',
                'email' => 'user8@aota.local',
                'displayname' => 'li si',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
        ));
	}
}
