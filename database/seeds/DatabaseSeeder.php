<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
		$this->call(UsersTableSeeder::class);
		$this->call(ConfigsTableSeeder::class);
		$this->call(RolesAndPermissionsSeeder::class);
		
        // $this->call(Smt_configsTableSeeder::class);
        $this->call(Renshi_jiabansTableSeeder::class);
    }
}
