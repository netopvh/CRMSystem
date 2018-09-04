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

        // 1 ЦУП
        [
            'name' => 'ЦУП',
            'icon' => 'icon-mcc',
            'alias' => 'admin/dashboard',
            'parent_id' => null,
            'page_id' => 31,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 1,
        ],
        [
            'name' => 'Компании',
            'icon' => null,
            'alias' => 'admin/companies',
            'parent_id' => 14,
            'page_id' => 1,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Пользователи',
            'icon' => null,
            'alias' => 'admin/users',
            'parent_id' => 36,
            'page_id' => 2,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Филиалы и отделы',
            'icon' => null,
            'alias' => 'admin/departments',
            'parent_id' => 36,
            'page_id' => 3,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Штат',
            'icon' => null,
            'alias' => 'admin/staff',
            'parent_id' => 36,
            'page_id' => 4,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Сотрудники',
            'icon' => null,
            'alias' => 'admin/employees',
            'parent_id' => 36,
            'page_id' => 5,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        // 7 Настройки
         [
            'name' => 'Настройки',
            'icon' => 'icon-settings',
            'alias' => null,
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 9,
        ],
         [
            'name' => 'Сущности',
            'icon' => null,
            'alias' => 'admin/entities',
            'parent_id' => 7,
            'page_id' => 6,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Роли',
            'icon' => null,
            'alias' => 'admin/roles',
            'parent_id' => 7,
            'page_id' => 7,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Права',
            'icon' => null,
            'alias' => 'admin/rights',
            'parent_id' => 7,
            'page_id' => 8,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        // 11 Маркетинг
        [
            'name' => 'Маркетинг',
            'icon' => 'icon-marketing',
            'alias' => null,
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 7,
        ],
        [
            'name' => 'Сайты',
            'icon' => null,
            'alias' => 'admin/sites',
            'parent_id' => 11,
            'page_id' => 9,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Альбомы',
            'icon' => null,
            'alias' => 'admin/albums',
            'parent_id' => 11,
            'page_id' => 12,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        // 14 Справочники
        [
            'name' => 'Справочники',
            'icon' => 'icon-guide',
            'alias' => null,
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 8,
        ],
        [
            'name' => 'Должности',
            'icon' => null,
            'alias' => 'admin/positions',
            'parent_id' => 36,
            'page_id' => 13,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Списки',
            'icon' => null,
            'alias' => 'admin/booklists',
            'parent_id' => 14,
            'page_id' => 14,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Секторы',
            'icon' => null,
            'alias' => 'admin/sectors',
            'parent_id' => 14,
            'page_id' => 15,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Папки',
            'icon' => null,
            'alias' => 'admin/folders',
            'parent_id' => 14,
            'page_id' => 16,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Категории альбомов',
            'icon' => null,
            'alias' => 'admin/albums_categories',
            'parent_id' => 11,
            'page_id' => 17,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        // 20 Продукция
        [
            'name' => 'Продукция',
            'icon' => 'icon-product',
            'alias' => null,
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 4,
        ],
        [
            'name' => 'Категории товаров',
            'icon' => null,
            'alias' => 'admin/goods_categories',
            'parent_id' => 20,
            'page_id' => 19,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 4,
        ],
        [
            'name' => 'Категории услуг',
            'icon' => null,
            'alias' => 'admin/services_categories',
            'parent_id' => 20,
            'page_id' => 20,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 5,
        ],
        [
            'name' => 'Категории сырья',
            'icon' => null,
            'alias' => 'admin/raws_categories',
            'parent_id' => 20,
            'page_id' => 21,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 6,
        ],

        [
            'name' => 'Товары',
            'icon' => null,
            'alias' => 'admin/goods',
            'parent_id' => 20,
            'page_id' => 22,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 1,
        ],
        [
            'name' => 'Услуги',
            'icon' => null,
            'alias' => 'admin/services',
            'parent_id' => 20,
            'page_id' => 23,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 2,
        ],
        [
            'name' => 'Сырьё',
            'icon' => null,
            'alias' => 'admin/raws',
            'parent_id' => 20,
            'page_id' => 24,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 3,
        ],

        [
            'name' => 'Помещения',
            'icon' => null,
            'alias' => 'admin/places',
            'parent_id' => 35,
            'page_id' => 25,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        // 28 Продажи
        [
            'name' => 'Продажи',
            'icon' => 'icon-sale',
            'alias' => null,
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 3,
        ],

        [
            'name' => 'Скрипты',
            'icon' => null,
            'alias' => 'admin/home',
            'parent_id' => 28,
            'page_id' => 26,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 3,
        ],
        
        [
            'name' => 'Населенные пункты',
            'icon' => null,
            'alias' => 'admin/cities',
            'parent_id' => 14,
            'page_id' => 14,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],


        // 31 Разделы сайта
        [
            'name' => 'Страницы',
            'icon' => null,
            'alias' => 'pages',
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 1,
            'company_id' => null,
            'system_item' => null,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Навигации',
            'icon' => null,
            'alias' => 'navigations',
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 1,
            'company_id' => null,
            'system_item' => null,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Новости',
            'icon' => null,
            'alias' => 'news',
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 1,
            'company_id' => null,
            'system_item' => null,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Каталог',
            'icon' => null,
            'alias' => 'catalogs',
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 1,
            'company_id' => null,
            'system_item' => null,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        // 35
        [
            'name' => 'Прoизводство',
            'icon' => 'icon-production',
            'alias' => null,
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 6,
        ],
        [
            'name' => 'Персонал',
            'icon' => 'icon-personals',
            'alias' => null,
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 2,
        ],
        [
            'name' => 'Поставщики',
            'icon' => null,
            'alias' => 'admin/suppliers',
            'parent_id' => 35,
            'page_id' => 29,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],
        [
            'name' => 'Клиенты',
            'icon' => null,
            'alias' => 'admin/clients',
            'parent_id' => 28,
            'page_id' => 30,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 2,
        ],
        [
            'name' => 'Производители',
            'icon' => null,
            'alias' => 'admin/manufacturers',
            'parent_id' => 14,
            'page_id' => 31,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        [
            'name' => 'Группы товаров',
            'icon' => null,
            'alias' => 'admin/goods_products',
            'parent_id' => 20,
            'page_id' => 33,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 7,
        ],

        [
            'name' => 'Группы услуг',
            'icon' => null,
            'alias' => 'admin/services_products',
            'parent_id' => 20,
            'page_id' => 34,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 8,
        ],

        [
            'name' => 'Группы сырья',
            'icon' => null,
            'alias' => 'admin/raws_products',
            'parent_id' => 20,
            'page_id' => 35,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 9,
        ],

        // Финансы 43
        [
            'name' => 'Финансы',
            'icon' => 'icon-finance',
            'alias' => null,
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 5,
        ],

        // 44
        [
            'name' => 'Операционные расходы',
            'icon' => null,
            'alias' => 'admin/expenses',
            'parent_id' => 43,
            'page_id' => 36,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 1,
        ],

        // 45
        [
            'name' => 'Зарплаты',
            'icon' => null,
            'alias' => 'admin/salaries',
            'parent_id' => 43,
            'page_id' => 37,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 2,
        ],

        // 46
        [
            'name' => 'Реклама',
            'icon' => null,
            'alias' => 'null',
            'parent_id' => 11,
            'page_id' => null,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        // 47
        [
            'name' => 'Рекламные кампании',
            'icon' => null,
            'alias' => 'admin/campaigns',
            'parent_id' => 46,
            'page_id' => 39,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 1,
        ],

        // 48
        [
            'name' => 'Социальные сети',
            'icon' => null,
            'alias' => 'admin/social_networks',
            'parent_id' => 46,
            'page_id' => 40,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 2,
        ],

        // 49
        [
            'name' => 'Лиды',
            'icon' => null,
            'alias' => 'admin/leads',
            'parent_id' => 28,
            'page_id' => 38,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => 1,
        ],

        // 50
        [
            'name' => 'Продукция на сайте',
            'icon' => null,
            'alias' => 'catalog_products',
            'parent_id' => null,
            'page_id' => null,
            'navigation_id' => 1,
            'company_id' => null,
            'system_item' => null,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        // 51
        [
            'name' => 'Этапы',
            'icon' => null,
            'alias' => 'admin/stages',
            'parent_id' => 7,
            'page_id' => 43,
            'navigation_id' => 2,
            'company_id' => null,
            'system_item' => 1,
            'author_id' => 1,
            'display' => 1,
            'sort' => null,
        ],

        // Остальное
                // [
        //     'name' => 'Типы помещений',
        //     'icon' => null,
        //     'alias' => 'places_types',
        //     'parent_id' => 4,
        //     'page_id' => 54,
        //     'navigation_id' => 2,
        //     'company_id' => null,
        //     'system_item' => 1,
        //     'author_id' => 1,
        //     'display' => 1,
        //     'sort' => null,
        // ],



    ]);
}
}
