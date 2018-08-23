<?php

use Illuminate\Database\Seeder;

class GoodsCategoriesTesterTableSeeder extends Seeder
{
    
    public function run()
    {
        DB::table('goods_categories')->insert([

            // 1
            [
                'company_id' => 1,
                'name' => 'Гаражные ворота',
                'description' => '', 
                'parent_id' => null, 
                'category_id' => null, 
                'category_status' => 1,               
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 2
            [
                'company_id' => 1,
                'name' => 'Уличные ворота',
                'description' => '', 
                'parent_id' => null, 
                'category_id' => null,
                'category_status' => 1,    
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 3
            [
                'company_id' => 1,
                'name' => 'Рольставни',
                'description' => '', 
                'parent_id' => null, 
                'category_id' => null,
                'category_status' => 1,                 
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 4
            [
                'company_id' => 1,
                'name' => 'Автоматика',
                'description' => '', 
                'parent_id' => null, 
                'category_id' => null,
                'category_status' => 1,                
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 5
            [
                'company_id' => 1,
                'name' => 'Заборы',
                'description' => '', 
                'parent_id' => null, 
                'category_id' => null, 
                'category_status' => 1,               
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 6
            [
                'company_id' => 1,
                'name' => 'Шлагбаумы',
                'description' => '', 
                'parent_id' => null, 
                'category_id' => null, 
                'category_status' => 1,                
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 7
            [
                'company_id' => 1,
                'name' => 'Перегрузочные системы',
                'description' => '', 
                'parent_id' => null, 
                'category_id' => null,
                'category_status' => 1,                 
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 8
            [
                'company_id' => 1,
                'name' => 'Алюминиевые конструкции',
                'description' => '', 
                'parent_id' => null, 
                'category_id' => null, 
                'category_status' => 1,               
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 9
            [
                'company_id' => 1,
                'name' => 'Секционные ворота',
                'description' => '', 
                'parent_id' => 1, 
                'category_id' => 1, 
                'category_status' => null,                 
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 10
            [
                'company_id' => 1,
                'name' => 'Секционные промышленные ворота',
                'description' => '', 
                'parent_id' => 1, 
                'category_id' => 1,   
                'category_status' => null,             
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 11
            [
                'company_id' => 1,
                'name' => 'Распашные ворота',
                'description' => '', 
                'parent_id' => 1, 
                'category_id' => 1,    
                'category_status' => null,     
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 12
            [
                'company_id' => 1,
                'name' => 'Откатные ворота',
                'description' => '', 
                'parent_id' => 2, 
                'category_id' => 2,     
                'category_status' => null,      
                'author_id' => 1, 
                'display' => 1, 
            ],

            // 13
            [
                'company_id' => 1,
                'name' => 'Распашные ворота',
                'description' => '', 
                'parent_id' => 2, 
                'category_id' => 2,   
                'category_status' => null,            
                'author_id' => 1, 
                'display' => 1, 
            ],


        ]);
    }
}
