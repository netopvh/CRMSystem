<?php

use Illuminate\Database\Seeder;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('albums')->insert([
    		[
    			'name' => 'Тест',
    			'alias' => 'test',
    			'albums_category_id' => 1,
    			'company_id' => 1, 
    			'author_id' => 4, 
    		],
    	]);
    }
}
