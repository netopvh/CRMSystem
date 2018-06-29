<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Создаем страницы для crm системы
     DB::table('pages')->insert([
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
        'name' => 'Сотрудники',
        'site_id' => 1,
        'title' => 'Сотрудники компани',
        'description' => 'Сотрудники компании',
        'alias' => 'employees',
        'company_id' => null,
        'system_item' => 1,
        'author_id' => 1,
        'display' => null,
      ],
      [
        'name' => 'Тестовая страница для должностей',
        'site_id' => 1,
        'title' => 'Страница должности',
        'description' => 'Должность в компании',
        'alias' => 'home',
        'company_id' => null,
        'system_item' => 1,
        'author_id' => 1,
        'display' => null,
      ],
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
        'name' => 'Главная',
        'site_id' => 2,
        'title' => 'Воротная компания "Марс"',
        'description' => 'Производство и профессиональный монтаж уличных и гаражных ворот от компании МАРС. Купить секционные или откатные ворота - просто как никогда!',
        'alias' => 'company',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
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
        'name' => 'Новости',
        'site_id' => 2,
        'title' => 'Новости',
        'description' => 'Новости, акции и готовые решения от воротной компании Марс. Телефон в Иркутске: 8 (3952) 71-77-75',
        'alias' => 'news',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Контакты',
        'site_id' => 2,
        'title' => 'Контакты',
        'description' => 'Контактные данные воротной компании Марс. Телефон в Иркутске: 8 (3952) 71-77-75',
        'alias' => 'contacts',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Замер',
        'site_id' => 2,
        'title' => 'Замер',
        'description' => 'Закажите бесплатный замер на приобретение уличных или гаражных ворот. Телефон в Иркутске: 8 (3952) 71-77-75',
        'alias' => 'measurement',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Гаражные ворота',
        'site_id' => 2,
        'title' => 'Гаражные ворота',
        'description' => 'Секционные гаражные ворота от Doorhan (Дорхан) в Иркутске. Купить автоматические подъемные ворота - быстро и без хлопот!',
        'alias' => 'garage_gates',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Уличные ворота',
        'site_id' => 2,
        'title' => 'Уличные ворота',
        'description' => 'Купить автоматические откатные ворота высокого качества - быстро и без хлопот: описание конструктива, выбор материалов, варианты дизайна',
        'alias' => 'street_gates',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Рольставни',
        'site_id' => 2,
        'title' => 'Рольставни',
        'description' => 'Продажа и монтаж рольворот и рольставень от производителя (Doorhan) в Иркутске. Высокое качество.',
        'alias' => 'rollets',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Автоматика',
        'site_id' => 2,
        'title' => 'Автоматика',
        'description' => 'Автоматика Doorhan: электроприводы для секционных, сдвижных, распашных ворот. Вы можете купить сейчас или заказать приводы Shaft, Sectional, Sliding, Swing, Arm и другие.',
        'alias' => 'automatics',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Стальные двери',
        'site_id' => 2,
        'title' => 'Стальные двери',
        'description' => 'Поставка и монтаж стальных дверей от Doorhan в Иркутске и Иркутской области.',
        'alias' => 'steel_doors',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => null,
      ],
      [
        'name' => 'Перегрузочные системы',
        'site_id' => 2,
        'title' => 'Перегрузочные системы',
        'description' => 'Перегрузочные системы от Doorhan: механические и электрогидравлические платформы.',
        'alias' => 'handling_systems',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Сервисный центр',
        'site_id' => 2,
        'title' => 'Сервисный центр',
        'description' => 'Сервисный центр Doorhan (Дорхан) в Иркутске. Ремонт автоматики - быстро и без хлопот!',
        'alias' => 'service_center',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'База знаний',
        'site_id' => 2,
        'title' => 'База знаний',
        'description' => 'Профессиональная команда воротной компании Марс: безупречное производство и качественный монтаж гаражных и уличных ворот',
        'alias' => 'knowledge',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Заборы',
        'site_id' => 2,
        'title' => 'Заборы',
        'description' => 'Купить автоматические откатные ворота высокого качества - быстро и без хлопот: описание конструктива, выбор материалов, варианты дизайна',
        'alias' => 'fences',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Ангары',
        'site_id' => 2,
        'title' => 'Ангары',
        'description' => 'Купить ангар в Иркутске, Ангарске. Модульные конструкции любого размера и для любых нужд',
        'alias' => 'hangars',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => null,
      ],
      [
        'name' => 'Шлагбаумы',
        'site_id' => 2,
        'title' => 'Шлагбаумы',
        'description' => 'Автоматика Doorhan: электроприводы для секционных, сдвижных, распашных ворот. Вы можете купить сейчас или заказать приводы Shaft, Sectional, Sliding, Swing, Arm и другие.',
        'alias' => 'barriers',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Противопожарные ворота',
        'site_id' => 2,
        'title' => 'Противопожарные ворота',
        'description' => 'Противопожарные ворота от Doorhan.',
        'alias' => 'fire_gates',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Команда',
        'site_id' => 2,
        'title' => 'Команда',
        'description' => 'Профессиональная команда воротной компании Марс: безупречное производство и качественный монтаж гаражных и уличных ворот',
        'alias' => 'team',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Вакансии',
        'site_id' => 2,
        'title' => 'Вакансии',
        'description' => 'Профессиональная команда воротной компании Марс: безупречное производство и качественный монтаж гаражных и уличных ворот',
        'alias' => 'vacancies',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
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
        'name' => 'Категории продукции',
        'site_id' => 1,
        'title' => 'Категории продукции',
        'description' => 'Категории продукции компании',
        'alias' => 'products_categories',
        'company_id' => null,
        'system_item' => 1,
        'author_id' => 1,
        'display' => 1,
      ],
      [
        'name' => 'Продукция',
        'site_id' => 1,
        'title' => 'Продукция',
        'description' => 'Продукция компании',
        'alias' => 'products',
        'company_id' => null,
        'system_item' => 1,
        'author_id' => 1,
        'display' => 1,
      ],
      [
        'name' => 'Товары',
        'site_id' => 5,
        'title' => 'Товары',
        'description' => 'Товары',
        'alias' => 'goods',
        'company_id' => 4,
        'system_item' => null,
        'author_id' => 1,
        'display' => 1,
      ],
      [
        'name' => 'Услуги',
        'site_id' => 5,
        'title' => 'Услуги',
        'description' => 'Услуги',
        'alias' => 'services',
        'company_id' => 4,
        'system_item' => null,
        'author_id' => 1,
        'display' => 1,
      ],
      [
        'name' => 'Контакты',
        'site_id' => 5,
        'title' => 'Контакты',
        'description' => 'Контакты',
        'alias' => 'contacts',
        'company_id' => 4,
        'system_item' => null,
        'author_id' => 1,
        'display' => 1,
      ],
      [
        'name' => 'Комплектация для откатных ворот',
        'site_id' => 2,
        'title' => 'Комплектация для откатных ворот',
        'description' => 'Готовая комплектация откатных уличных ворот для тех, кто решил их сделать своими руками: автоматика, балки, роликовые опоры, ловители, удерживающие устройства и многое другое.',
        'alias' => 'street_gate_set',
        'company_id' => 1,
        'system_item' => null,
        'author_id' => 7,
        'display' => 1,
      ],
      [
        'name' => 'Студия',
        'site_id' => 5,
        'title' => 'Студия штор',
        'description' => 'Студия штор',
        'alias' => 'studio',
        'company_id' => 4,
        'system_item' => null,
        'author_id' => 15,
        'display' => 1,
      ],

      [
        'name' => 'Категории товаров',
        'site_id' => 1,
        'title' => 'Категории товаров',
        'description' => 'Категории товаров',
        'alias' => 'products_categories/goods',
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
        'alias' => 'products_categories/services',
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
        'alias' => 'products_categories/raws',
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
        'alias' => 'products/goods',
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
        'alias' => 'products/services',
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
        'alias' => 'products/raws',
        'company_id' => null,
        'system_item' => 1,
        'author_id' => 1,
        'display' => null,
      ],
      
    ]);
}
}
