<?php

use Illuminate\Database\Seeder;

class EntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('entities')->insert([
       [
        'name' => 'Пользователи',
        'alias' => 'users',
        'model' => 'User',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Компании',
        'alias' => 'companies',
        'model' => 'Company',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Штат',
        'alias' => 'staff',
        'model' => 'Staffer',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Отделы',
        'alias' => 'departments',
        'model' => 'Department',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Должности',
        'alias' => 'positions',
        'model' => 'Position',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Сотрудники',
        'alias' => 'employees',
        'model' => 'Employee',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Области',
        'alias' => 'regions',
        'model' => 'Region',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Районы',
        'alias' => 'areas',
        'model' => 'Area',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Населенные пункты',
        'alias' => 'cities',
        'model' => 'City',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Категории правил',
        'alias' => 'category_right',
        'model' => 'Category_right',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Сущности',
        'alias' => 'entities',
        'model' => 'Entity',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Сайты',
        'alias' => 'sites',
        'model' => 'Site',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Страницы',
        'alias' => 'pages',
        'model' => 'Page',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Навигации',
        'alias' => 'navigations',
        'model' => 'Navigation',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Категории навигаци',
        'alias' => 'navigations_categories',
        'model' => 'NavigationsCategory',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Меню',
        'alias' => 'menus',
        'model' => 'Menu',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Правила',
        'alias' => 'rights',
        'model' => 'Right',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Роли',
        'alias' => 'roles',
        'model' => 'Role',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Списки',
        'alias' => 'booklists',
        'model' => 'Booklist',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Секторы',
        'alias' => 'sectors',
        'model' => 'Sector',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Папки',
        'alias' => 'folders',
        'model' => 'Folder',
        'system_item' => 1,
        'author_id' => 1,
      ],  
      [
        'name' => 'Альбомы',
        'alias' => 'albums',
        'model' => 'Album',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Категории альбомов',
        'alias' => 'albums_categories',
        'model' => 'AlbumsCategory',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Фотографии',
        'alias' => 'photos',
        'model' => 'Photo',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Сущности с прикрепленными альбомами',
        'alias' => 'album_entity',
        'model' => 'AlbumEntity',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Новости',
        'alias' => 'news',
        'model' => 'News',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Типы продукции',
        'alias' => 'products_types',
        'model' => 'ProductsType',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Категории продукции',
        'alias' => 'products_categories',
        'model' => 'ProductsCategory',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Продукция',
        'alias' => 'products',
        'model' => 'Product',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Единицы измерения',
        'alias' => 'units',
        'model' => 'Unit',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Страны',
        'alias' => 'countries',
        'model' => 'Country',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Сущности связанные с городами',
        'alias' => 'city_entity',
        'model' => 'CityEntity',
        'system_item' => 1,
        'author_id' => 1,
      ],
      [
        'name' => 'Расписания',
        'alias' => 'schedules',
        'model' => 'Schedule',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Локации',
        'alias' => 'locations',
        'model' => 'Location',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Настройки',
        'alias' => 'settings',
        'model' => 'Setting',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Метрики',
        'alias' => 'metrics',
        'model' => 'Metric',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Состав',
        'alias' => 'compositions',
        'model' => 'Composition',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Артикулы',
        'alias' => 'articles',
        'model' => 'Article',
        'system_item' => 1,
        'author_id' => 1,
      ], 
      [
        'name' => 'Значения',
        'alias' => 'values',
        'model' => 'Value',
        'system_item' => 1,
        'author_id' => 1,
      ], 
            // [
            //     'name' => 'Рабочее время дня',
            //     'alias' => 'worktimes',
            //     'model' => 'Worktime',
            //     'system_item' => 1,
            //     'author_id' => 1,
            // ], 

      
            // Связующие сущности

            // [
            //     'name' => 'Связь Право-Роль',
            //     'alias' => 'right_role',
            //     'author_id' => 1,
            // ],
            // [
            //     'name' => 'Связь Роль-Пользователь',
            //     'alias' => 'role_user',
            //     'author_id' => 1,
            // ],
            // [
            //     'name' => 'Связь Действие-Сущность',
            //     'alias' => 'action_entity',
            //     'author_id' => 1,
            // ],
      
    ]);
}
}
