<?php

use Illuminate\Database\Seeder;

use App\Sector;

class SectorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sector::insert([

            // 1
            [
                'name' => 'Строительство',
                'parent_id' => null,
            ],

            // 2
            [
                'name' => 'Производство',
                'parent_id' => null,
            ],

            // 3
            [
                'name' => 'Транспорт',
                'parent_id' => null,
            ],


            // 4
            [
                'name' => 'Дом, ремонт',
                'parent_id' => null,
            ],

            // 5
            [
                'name' => 'Двери и окна',
                'parent_id' => 4,
            ],

            // 6
            [
                'name' => 'Дизайн студии',
                'parent_id' => 4,
            ],

            // 7
            [
                'name' => 'Мебель и предметы интерьера',
                'parent_id' => 4,
            ],

            // 8
            [
                'name' => 'Текстиль',
                'parent_id' => 4,
            ],

            // 9
            [
                'name' => 'Продукты питания',
                'parent_id' => null,
            ],

            // 10
            [
                'name' => 'Продукты',
                'parent_id' => 9,
            ],

            // 11
            [
                'name' => 'Доставка еды',
                'parent_id' => 9,
            ],

            // 12
            [
                'name' => 'Интернет',
                'parent_id' => null,
            ],

            // 13
            [
                'name' => 'Торговля',
                'parent_id' => null,
            ],

            // 14
            [
                'name' => 'Туризм',
                'parent_id' => null,
            ],

            // 15
            [
                'name' => 'Отели, хостелы',
                'parent_id' => 14,
            ],

            // 16
            [
                'name' => 'Туры и турагентства',
                'parent_id' => 14,
            ],

            // 17
            [
                'name' => 'Медицина',
                'parent_id' => null,
            ],

            // 18
            [
                'name' => 'Аптеки',
                'parent_id' => 17,
            ],

            // 19
            [
                'name' => 'Косметология и пластическая хирургия',
                'parent_id' => 17,
            ],

            // 20
            [
                'name' => 'Оптика',
                'parent_id' => 17,
            ],

            // 21
            [
                'name' => 'Психология',
                'parent_id' => 17,
            ],

            // 22
            [
                'name' => 'Стоматология',
                'parent_id' => 17,
            ],

            // 23
            [
                'name' => 'Заборы',
                'parent_id' => 1,
            ],

            // 24
            [
                'name' => 'Ворота',
                'parent_id' => 1,
            ],

            // 25
            [
                'name' => 'Грузоперевозки',
                'parent_id' => 3,
            ],

            // 26
            [
                'name' => 'Логистика',
                'parent_id' => 3,
            ],

            // 27
            [
                'name' => 'Такси',
                'parent_id' => 3,
            ],

            // 28
            [
                'name' => 'Недвижимость',
                'parent_id' => null,
            ],

            // 29
            [
                'name' => 'Агенство недвижимости',
                'parent_id' => 28,
            ],

            // 30
            [
                'name' => 'Аренда недвижимости',
                'parent_id' => 28,
            ],

            // 31
            [
                'name' => 'Жилая недвижимость',
                'parent_id' => 28,
            ],

            // 32
            [
                'name' => 'Загородная недвижимость',
                'parent_id' => 28,
            ],

            // 33
            [
                'name' => 'Одежда и обувь',
                'parent_id' => null,
            ],

            // 34
            [
                'name' => 'Аксессуары',
                'parent_id' => 33,
            ],

            // 35
            [
                'name' => 'Мужская и женская одежда',
                'parent_id' => 33,
            ],

            // 36
            [
                'name' => 'Подарки и сувениры',
                'parent_id' => null,
            ],

            // 37
            [
                'name' => 'Магазин подарков',
                'parent_id' => 36,
            ],

            // 38
            [
                'name' => 'Услуги по организации праздников',
                'parent_id' => 36,
            ],

            // 39
            [
                'name' => 'Финансовые услуги',
                'parent_id' => null,
            ],

            // 40
            [
                'name' => 'Банк',
                'parent_id' => 39,
            ],
        ]);

        foreach (Sector::get() as $sector) {
            $tag = Transliterate::make($sector->name, ['type' => 'url', 'lowercase' => true]);
            Sector::where('id', $sector->id)->update(['author_id' => 1, 'tag' => $tag, 'category_id' => $sector->parent_id]);
        }
    }
}
