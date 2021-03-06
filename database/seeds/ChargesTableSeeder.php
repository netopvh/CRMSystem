<?php

use Illuminate\Database\Seeder;

class ChargesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('charges')->insert([
        	[
		        'name' => 'Назначение лида',
		        'description' => 'Назначение лида кому либо',
		        'alias' => 'lead-appointment',
                'author_id' => 1,
        	],
            [
                'name' => 'Прием лида',
                'description' => null,
                'alias' => 'lead-regular',
                'author_id' => 1,
            ],
            [
                'name' => 'Прием лида в сервисный центр',
                'description' => null,
                'alias' => 'lead-service',
                'author_id' => 1,
            ],
            [
                'name' => 'Прием дилера',
                'description' => null,
                'alias' => 'lead-dealer',
                'author_id' => 1,
            ],
            [
                'name' => 'Работа с потенциальным лидом',
                'description' => null,
                'alias' => 'stage-potential',
                'author_id' => 1,
            ],
            [
                'name' => 'Просмотр свободных заявок',
                'description' => null,
                'alias' => 'lead-free',
                'author_id' => 1,
            ],
            [
                'name' => 'Просмотр лидов без ограничений по менеджерам',
                'description' => null,
                'alias' => 'lead-all-managers',
                'author_id' => 1,
            ],
            [
                'name' => 'Переназначение своего лида',
                'description' => null,
                'alias' => 'lead-appointment-self',
                'author_id' => 1,
            ],
            [
                'name' => 'Снятие задач для лида без ограничений',
                'description' => null,
                'alias' => 'lead-challenge-remove',
                'author_id' => 1,
            ],



        ]);
    }
}
