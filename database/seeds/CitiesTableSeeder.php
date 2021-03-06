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
		        'name' => 'Иркутск',
                'alias' => 'irkutsk',
		        // 'area_id' => '',
		        'region_id' => 1,
		        'code' => 83952,
		        'vk_external_id' => 57,
                'system_item' => 1,
        	],
            [
                'name' => 'Улан-Удэ',
                'alias' => 'ulanude',
                // 'area_id' => '',
                'region_id' => 2,
                'code' => null,
                'vk_external_id' => 148,
                'system_item' => 1,
            ],
            [
                'name' => 'Красноярск',
                'alias' => 'krasnoyarsk',
                // 'area_id' => '',
                'region_id' => 3,
                'code' => null,
                'vk_external_id' => 73,
                'system_item' => 1,
            ],
            [
                'name' => 'Чита',
                'alias' => 'chita',
                // 'area_id' => '',
                'region_id' => 4,
                'code' => null,
                'vk_external_id' => 161,
                'system_item' => 1,
            ],

        ]);
    }
}
