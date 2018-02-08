<?php

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
        	[
		        'city_name' => 'Иркутск',
		        // 'area_id' => '',
		        'region_id' => '1',
		        'city_code' => '83952',
		        'city_vk_external_id' => 57,
                'system_item' => 1,
        	],
            [
                'city_name' => 'Улан-Удэ',
                // 'area_id' => '',
                'region_id' => '2',
                'city_code' => null,
                'city_vk_external_id' => 148,
                'system_item' => 1,
            ],
            [
                'city_name' => 'Красноярск',
                // 'area_id' => '',
                'region_id' => '3',
                'city_code' => null,
                'city_vk_external_id' => 73,
                'system_item' => 1,
            ],

        ]);
    }
}
