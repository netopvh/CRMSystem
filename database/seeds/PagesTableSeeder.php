<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{

    public function run()
    {
        // Создаем страницы для crm системы
        DB::table('pages')->insert([
            // 1 ЦУП
            [
                'name' => 'Компании',
                'site_id' => 1,
                'title' => 'Компании',
                'description' => 'Компании в системе автоматизации',
                'alias' => 'companies',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Пользователи системы',
                'site_id' => 1,
                'title' => 'Пользователи системы',
                'description' => 'Пользователи системы',
                'alias' => 'users',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Штат',
                'site_id' => 1,
                'title' => 'Штат компании',
                'description' => 'Штат компании',
                'alias' => 'staff',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Филиалы и отделы',
                'site_id' => 1,
                'title' => 'Филиалы и отделы',
                'description' => 'Филиалы и отделы',
                'alias' => 'departments',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Сотрудники',
                'site_id' => 1,
                'title' => 'Сотрудники компании',
                'description' => 'Сотрудники компании',
                'alias' => 'employees',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],

            // 6 Настройка
            [
                'name' => 'Сущности',
                'site_id' => 1,
                'title' => 'Сущности',
                'description' => 'Сущности системы',
                'alias' => 'entities',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Роли',
                'site_id' => 1,
                'title' => 'Группы доступа (роли)',
                'description' => 'Пользовательские группы доступа',
                'alias' => 'roles',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Правила доступа',
                'site_id' => 1,
                'title' => 'Правила доступа зерегистрированные для системы',
                'description' => 'Правила доступа',
                'alias' => 'rights',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],

            // 9 Маркетинг
            [
                'name' => 'Сайты',
                'site_id' => 1,
                'title' => 'Сайты компании',
                'description' => 'Сайты компаний в системе, и сама система',
                'alias' => 'sites',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Страницы сайта',
                'site_id' => 1,
                'title' => 'Страницы сайта',
                'description' => 'Страницы определенного сайта',
                'alias' => 'pages',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Новости',
                'site_id' => 1,
                'title' => 'Новости',
                'description' => 'Новости',
                'alias' => 'news',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => 1,
            ],
            [
                'name' => 'Альбомы',
                'site_id' => 1,
                'title' => 'Альбомы',
                'description' => 'Альбомы фотографий компании',
                'alias' => 'albums',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => 1,
            ],

            // 13 Списки
            [
                'name' => 'Должности',
                'site_id' => 1,
                'title' => 'Должности',
                'description' => 'Должности компании',
                'alias' => 'positions',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Списки',
                'site_id' => 1,
                'title' => 'Списки',
                'description' => 'Списки любых сущностей',
                'alias' => 'booklists',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Секторы',
                'site_id' => 1,
                'title' => 'Секторы',
                'description' => 'Секторы',
                'alias' => 'sectors',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Папки',
                'site_id' => 1,
                'title' => 'Папки',
                'description' => 'Папки (директории)',
                'alias' => 'folders',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Категории альбомов',
                'site_id' => 1,
                'title' => 'Категории альбомов',
                'description' => 'Категории альбомов фотографий компании',
                'alias' => 'albums_categories',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => 1,
            ],
            [
                'name' => 'Фотографии альбома',
                'site_id' => 1,
                'title' => 'Фотографии альбома',
                'description' => 'Фотографии',
                'alias' => 'photos',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => 1,
            ],

            // 19 Продукция
            [
                'name' => 'Категории товаров',
                'site_id' => 1,
                'title' => 'Категории товаров',
                'description' => 'Категории товаров',
                'alias' => 'goods_categories',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Категории услуг',
                'site_id' => 1,
                'title' => 'Категории услуг',
                'description' => 'Категории услуг',
                'alias' => 'services_categories',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Категории сырья',
                'site_id' => 1,
                'title' => 'Категории сырья',
                'description' => 'Категории сырья',
                'alias' => 'raws_categories',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],

            [
                'name' => 'Товары',
                'site_id' => 1,
                'title' => 'Товары',
                'description' => 'Товары',
                'alias' => 'goods',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Услуги',
                'site_id' => 1,
                'title' => 'Услуги',
                'description' => 'Услуги',
                'alias' => 'services',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Сырьё',
                'site_id' => 1,
                'title' => 'Сырьё',
                'description' => 'Сырьё',
                'alias' => 'raws',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Помещения',
                'site_id' => 1,
                'title' => 'Помещения',
                'description' => 'Помещения',
                'alias' => 'places',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],

            [
                'name' => 'Продажи',
                'site_id' => 1,
                'title' => 'Страница должности',
                'description' => 'Должность в компании',
                'alias' => 'home',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],

            // 27 Страницы, связанные со многими сущностями
            [
                'name' => 'Навигации',
                'site_id' => 1,
                'title' => 'Навигации сайта',
                'description' => 'Навигации',
                'alias' => 'navigations',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Населенные пункты',
                'site_id' => 1,
                'title' => 'Населенные пункты',
                'description' => 'Области, районы и города',
                'alias' => 'cities',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Поставщики',
                'site_id' => 1,
                'title' => 'Поставщики',
                'description' => 'Поставщики товаров и услуг',
                'alias' => 'suppliers',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],
            [
                'name' => 'Клиенты',
                'site_id' => 1,
                'title' => 'Клиенты',
                'description' => 'Все наши клиенты: от физического лица до юридических организаций.',
                'alias' => 'clients',
                'company_id' => null,
                'system_item' => 1,
                'author_id' => 1,
                'display' => null,
            ],


            // [
            //     'name' => 'Категории продукции',
            //     'site_id' => 1,
            //     'title' => 'Категории продукции',
            //     'description' => 'Категории продукции компании',
            //     'alias' => 'products_categories',
            //     'company_id' => null,
            //     'system_item' => 1,
            //     'author_id' => 1,
            //     'display' => 1,
            // ],
            // [
            //     'name' => 'Продукция',
            //     'site_id' => 1,
            //     'title' => 'Продукция',
            //     'description' => 'Продукция компании',
            //     'alias' => 'products',
            //     'company_id' => null,
            //     'system_item' => 1,
            //     'author_id' => 1,
            //     'display' => 1,
            // ],
            // [
            //     'name' => 'Типы помещений',
            //     'site_id' => 1,
            //     'title' => 'Типы помещений',
            //     'description' => 'Типы помещений',
            //     'alias' => 'places_types',
            //     'company_id' => null,
            //     'system_item' => 1,
            //     'author_id' => 1,
            //     'display' => null,
            // ],
        ]);
}
}
