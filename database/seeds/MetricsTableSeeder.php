<?php

use Illuminate\Database\Seeder;

class MetricsTableSeeder extends Seeder
{

    public function run()
    {
    	DB::table('metrics')->insert([
    		[
    			'name' => 'Ширина откатных ворот',
    			'property_id' => 1,
    			'min' => 2,
    			'max' => 7,
    			'color' => null,
    			'boolean_true' => null,
    			'boolean_false' => null,
    			'booklist_id' => null,
                'company_id' => 1,
                'unit_id' => 4,
    		],
    		[
    			'name' => 'Высота откатных ворот',
    			'property_id' => 3,
    			'min' => 1,
    			'max' => 3,
    			'color' => null,
    			'boolean_true' => null,
    			'boolean_false' => null,
    			'booklist_id' => null,
                'company_id' => 1,
                'unit_id' => 4,
    		],

            // Шторка
            [
                'name' => 'Полиэстер',
                'property_id' => 10,
                'min' => 0,
                'max' => 100,
                'color' => null,
                'boolean_true' => null,
                'boolean_false' => null,
                'booklist_id' => null,
                'company_id' => 4,
                'unit_id' => 31,
            ],
            [
                'name' => 'Хлопок',
                'property_id' => 10,
                'min' => 0,
                'max' => 100,
                'color' => null,
                'boolean_true' => null,
                'boolean_false' => null,
                'booklist_id' => null,
                'company_id' => 4,
                'unit_id' => 31,
            ],
            [
                'name' => 'Бамбук',
                'property_id' => 10,
                'min' => 0,
                'max' => 100,
                'color' => null,
                'boolean_true' => null,
                'boolean_false' => null,
                'booklist_id' => null,
                'company_id' => 4,
                'unit_id' => 31,
            ],
            [
                'name' => 'Ранфорс',
                'property_id' => 10,
                'min' => 0,
                'max' => 100,
                'color' => null,
                'boolean_true' => null,
                'boolean_false' => null,
                'booklist_id' => null,
                'company_id' => 4,
                'unit_id' => 31,
            ],
            [
                'name' => 'Сатин',
                'property_id' => 10,
                'min' => 0,
                'max' => 100,
                'color' => null,
                'boolean_true' => null,
                'boolean_false' => null,
                'booklist_id' => null,
                'company_id' => 4,
                'unit_id' => 31,
            ],
            [
                'name' => 'Микрофибра',
                'property_id' => 10,
                'min' => 0,
                'max' => 100,
                'color' => null,
                'boolean_true' => null,
                'boolean_false' => null,
                'booklist_id' => null,
                'company_id' => 4,
                'unit_id' => 31,
            ],
    	]);
    }
}
