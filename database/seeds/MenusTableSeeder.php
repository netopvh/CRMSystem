<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [
                'menu_name' => 'ЦУП',
                'menu_icon' => 'icon-mcc',
                'menu_alias' => null,
                'menu_parent_id' => null,
                'page_id' => null,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Тест для сотрудников',
                'menu_icon' => 'icon-sale',
                'menu_alias' => null,
                'menu_parent_id' => null,
                'page_id' => null,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Маркетинг',
                'menu_icon' => 'icon-marketing',
                'menu_alias' => null,
                'menu_parent_id' => null,
                'page_id' => null,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Справочники',
                'menu_icon' => 'icon-guide',
                'menu_alias' => null,
                'menu_parent_id' => null,
                'page_id' => null,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Настройки',
                'menu_icon' => 'icon-settings',
                'menu_alias' => null,
                'menu_parent_id' => null,
                'page_id' => null,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
            ],
        	[
		        'menu_name' => 'Компании',
                'menu_icon' => null,
                'menu_alias' => 'companies',
                'menu_parent_id' => 1,
		        'page_id' => 1,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Пользователи',
                'menu_icon' => null,
                'menu_alias' => 'users',
                'menu_parent_id' => 1,
		        'page_id' => 2,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Филиалы и отделы',
                'menu_icon' => null,
                'menu_alias' => 'departments',
                'menu_parent_id' => 1,
		        'page_id' => 3,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Штат',
                'menu_icon' => null,
                'menu_alias' => 'staff',
                'menu_parent_id' => 1,
		        'page_id' => 4,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Сотрудники',
                'menu_icon' => null,
                'menu_alias' => 'employees',
                'menu_parent_id' => 1,
		        'page_id' => 5,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Тестовая',
                'menu_icon' => null,
                'menu_alias' => 'home',
                'menu_parent_id' => 2,
		        'page_id' => 6,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Сайты',
                'menu_icon' => null,
                'menu_alias' => 'sites',
                'menu_parent_id' => 3,
		        'page_id' => 7,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Населенные пункты',
                'menu_icon' => null,
                'menu_alias' => 'cities',
                'menu_parent_id' => 4,
		        'page_id' => 8,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Должности',
                'menu_icon' => null,
                'menu_alias' => 'positions',
                'menu_parent_id' => 4,
		        'page_id' => 9,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Сущности',
                'menu_icon' => null,
                'menu_alias' => 'entities',
                'menu_parent_id' => 5,
		        'page_id' => 10,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
        	[
		        'menu_name' => 'Роли',
                'menu_icon' => null,
                'menu_alias' => 'roles',
                'menu_parent_id' => 5,
		        'page_id' => 11,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
        	],
            [
                'menu_name' => 'Права',
                'menu_icon' => null,
                'menu_alias' => 'rights',
                'menu_parent_id' => 5,
                'page_id' => 12,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Страницы',
                'menu_icon' => null,
                'menu_alias' => 'pages',
                'menu_parent_id' => null,
                'page_id' => null,
                'navigation_id' => 1,
                'system_item' => null,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Навигации',
                'menu_icon' => null,
                'menu_alias' => 'navigations',
                'menu_parent_id' => null,
                'page_id' => null,
                'navigation_id' => 1,
                'system_item' => null,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Новости',
                'menu_icon' => null,
                'menu_alias' => 'news',
                'menu_parent_id' => null,
                'page_id' => null,
                'navigation_id' => 1,
                'system_item' => null,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Галерея',
                'menu_icon' => null,
                'menu_alias' => 'gallery',
                'menu_parent_id' => null,
                'page_id' => null,
                'navigation_id' => 1,
                'system_item' => null,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Списки',
                'menu_icon' => null,
                'menu_alias' => 'booklists',
                'menu_parent_id' => 4,
                'page_id' => 16,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Секторы',
                'menu_icon' => null,
                'menu_alias' => 'sectors',
                'menu_parent_id' => 4,
                'page_id' => 17,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
            ],
            [
                'menu_name' => 'Папки',
                'menu_icon' => null,
                'menu_alias' => 'folders',
                'menu_parent_id' => 4,
                'page_id' => 18,
                'navigation_id' => 2,
                'system_item' => 1,
                'author_id' => 1,
            ],
            
        // [
        //  'menu_name' => null,
        //        'menu_icon' => null,
        //        'menu_parent_id' => 3,
        //  'page_id' => 8,
        //        'navigation_id' => 2,
        // ],

        ]);
    }
}
