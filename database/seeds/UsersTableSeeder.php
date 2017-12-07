<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	[
	        	'login' => 'creativebob', 
	        	'email' => 'creativebob@mail.ru', 
	        	'password' => bcrypt('111111'), 
	        	'nickname' => 'creativebob', 
	        	'phone' => '89041248598', 
	        	'group_users_id' => 1, 
	        	'group_filials_id' => 2, 
	        	'contragent_status' => 1, 
	        	'access_block' => 0, 
        	] 
        ]);
    }
}
