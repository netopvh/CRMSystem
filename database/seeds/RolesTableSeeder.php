<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
        	[
                'name' => 'Полный доступ', 
                'company_id' => null, 
                'system_item' => null, 
                'author_id' => 1
            ], 
            [
                'name' => 'Директор', 
                'company_id' => null, 
                'system_item' => null, 
                'author_id' => 1
            ], 
            [
                'name' => 'Менеджер', 
                'company_id' => null, 
                'system_item' => null, 
                'author_id' => 1
            ], 
            [
                'name' => 'Маркетолог', 
                'company_id' => null, 
                'system_item' => null, 
                'author_id' => 1
            ], 
            [
                'name' => 'Управляющий персоналом', 
                'company_id' => null, 
                'system_item' => null, 
                'author_id' => 1
            ],
            // [
            //        'name' => 'Администратор', 
            //        'company_id' => null, 
            //        'system_item' => null, 
            //        'author_id' => 1
            //    ], 
 
        ]);
    }
}
