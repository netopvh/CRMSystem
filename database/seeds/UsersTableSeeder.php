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
    			'login' => 'robot', 
    			'email' => 'robocop@mail.ru', 
    			'password' => bcrypt('111111'), 
    			'nickname' => 'robot', 
    			'first_name' => 'Super',
    			'second_name' => 'User',
    			'phone' => 89000000000, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => null, 
    			'filial_id' => null,
    			'god' => null, 
    			'filial_id' => null, 
    			'system_item' => 1, 
    			'author_id' => null, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'creativebob', 
    			'email' => 'creativebob@mail.ru', 
    			'password' => bcrypt('111111'), 
    			'nickname' => 'creativebob', 
    			'first_name' => 'Nestor',
    			'second_name' => 'Господин',
    			'phone' => 89041248598, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => null, 
    			'filial_id' => null,
    			'god' => 1, 
    			'filial_id' => null, 
    			'system_item' => null, 
    			'author_id' => 1,
    			'moderation' => null,
    		],
    		[
    			'login' => 'makc_berluskone', 
    			'email' => 'makc_berluskone@mail.ru', 
    			'password' => bcrypt('123456'), 
    			'nickname' => 'Makc_Berluskone', 
    			'first_name' => 'Максон',
    			'second_name' => 'Великий',
    			'phone' => 88888888888, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1,
    			'filial_id' => null, 
    			'god' => 1, 
    			'filial_id' => null, 
    			'system_item' => null, 
    			'author_id' => 1, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'timoshenko', 
    			'email' => 'sidorov@mail.ru', 
    			'password' => bcrypt('123456'), 
    			'nickname' => 'timoshenko', 
    			'first_name' => 'Алексей',
    			'second_name' => 'Тимошенко',
    			'phone' => 89024673334, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 1,
    			'god' => null, 
    			'filial_id' => 1, 
    			'system_item' => null, 
    			'author_id' => 1, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'mironov', 
    			'email' => 'mironov@mail.ru', 
    			'password' => bcrypt('123456'), 
    			'nickname' => 'mironov', 
    			'first_name' => 'Юрий',
    			'second_name' => 'Миронов',
    			'phone' => 89024112734, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 1,
    			'god' => null, 
    			'filial_id' => 1, 
    			'system_item' => null, 
    			'author_id' => 4, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'kondrachuk', 
    			'email' => 'kondrachuk@mail.ru', 
    			'password' => bcrypt('123456'), 
    			'nickname' => 'kondrachuk', 
    			'first_name' => 'Анна',
    			'second_name' => 'Кондрачук',
    			'phone' => 86784672734, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 1,
    			'god' => null, 
    			'filial_id' => 1, 
    			'system_item' => null, 
    			'author_id' => 4, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'davydenko', 
    			'email' => 'd@vorotamars.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Максим',
    			'second_name' => 'Давыденко',
    			'nickname' => 'davydenko', 
    			'phone' => 89025687585, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 1,
    			'god' => null, 
    			'filial_id' => 1, 
    			'system_item' => null, 
    			'author_id' => 4, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'ylan1', 
    			'email' => 'ylan1@vorotamars.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Улан',
    			'second_name' => 'Первый',
    			'nickname' => 'ylan1', 
    			'phone' => 81111111111, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 2,
    			'god' => null, 
    			'system_item' => null, 
    			'author_id' => 4, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'ylan2', 
    			'email' => 'ylan2@vorotamars.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Улан',
    			'second_name' => 'Второй',
    			'nickname' => 'ylan2', 
    			'phone' => 81111111112, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 2,
    			'god' => null, 
    			'system_item' => null, 
    			'author_id' => 8, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'ylan3', 
    			'email' => 'ylan3@vorotamars.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Улан',
    			'second_name' => 'Третий',
    			'nickname' => 'ylan3', 
    			'phone' => 81111111113, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 2,
    			'god' => null, 
    			'system_item' => null, 
    			'author_id' => 8, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'chita1', 
    			'email' => 'chita1@vorotamars.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Чита',
    			'second_name' => 'Первый',
    			'nickname' => 'chita1', 
    			'phone' => 81111111114, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 2,
    			'god' => null, 
    			'system_item' => null, 
    			'author_id' => 4, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'chita2', 
    			'email' => 'chita2@vorotamars.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Чита',
    			'second_name' => 'Второй',
    			'nickname' => 'chita2', 
    			'phone' => 81111111115, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 2,
    			'god' => null, 
    			'system_item' => null, 
    			'author_id' => 11, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'chita3', 
    			'email' => 'chita3@vorotamars.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Чита',
    			'second_name' => 'Третий',
    			'nickname' => 'chita3', 
    			'phone' => 81111111116, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 2,
    			'god' => null, 
    			'system_item' => null, 
    			'author_id' => 12, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'fenster', 
    			'email' => 'fenster@mail.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Дирик',
    			'second_name' => 'Фенстер',
    			'nickname' => 'fenster', 
    			'phone' => 81111111117, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 2, 
    			'filial_id' => 4,
    			'god' => null, 
    			'system_item' => null, 
    			'author_id' => 1, 
    			'moderation' => null, 
    		],
    		[
    			'login' => 'test1', 
    			'email' => 'test1@mail.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Неотмодерированный',
    			'second_name' => 'Первый',
    			'nickname' => 'nomoder', 
    			'phone' => 81111111118, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 1,
    			'god' => null, 
    			'system_item' => null, 
    			'author_id' => 5, 
    			'moderation' => 1, 
    		],
    		[
    			'login' => 'test2', 
    			'email' => 'test2@mail.ru', 
    			'password' => bcrypt('123456'), 
    			'first_name' => 'Неотмодерированный',
    			'second_name' => 'Второй',
    			'nickname' => 'nomoder', 
    			'phone' => 81111111119, 
    			'user_type' => 1, 
    			'access_block' => 0, 
    			'company_id' => 1, 
    			'filial_id' => 1,
    			'god' => null, 
    			'system_item' => null, 
    			'author_id' => 5, 
    			'moderation' => 1, 
    		],
    	]);

}
}
