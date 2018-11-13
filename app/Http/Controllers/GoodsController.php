<?php

namespace App\Http\Controllers;

// Модели
use App\Goods;
use App\GoodsCategory;
use App\GoodsMode;
use App\GoodsProduct;
use App\GoodsArticle;
use App\Album;
use App\AlbumEntity;
use App\Photo;
use App\UnitsCategory;
use App\Unit;
use App\Catalog;

use App\RawsArticle;
use App\EntitySetting;
use App\ArticleValue;

// Политика
use App\Policies\GoodsPolicy;

// Куки
use Illuminate\Support\Facades\Cookie;

// Транслитерация
use Transliterate;


use Illuminate\Http\Request;

class GoodsController extends Controller
{

    // Сущность над которой производит операции контроллер
    protected $entity_name = 'goods';
    protected $entity_dependence = false;

    public function index(Request $request)
    {

        // Подключение политики
        $this->authorize('index', Goods::class);

        // Включение контроля активного фильтра
        $filter_url = autoFilter($request, $this->entity_name);

        if (($filter_url != null)&&($request->filter != 'active')) {
            Cookie::queue(Cookie::forget('filter_' . $this->entity_name));
            return Redirect($filter_url);
        }

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // -----------------------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // -----------------------------------------------------------------------------------------------------------------------------

        $goods = Goods::with('author', 'company', 'goods_article.goods_product.goods_category', 'catalogs.site')
        ->moderatorLimit($answer)
        ->companiesLimit($answer)
        ->authors($answer)
        ->systemItem($answer) // Фильтр по системным записям
        ->booklistFilter($request)
        ->filter($request, 'author_id')
        ->filter($request, 'goods_category_id', 'goods_article.goods_product')
        ->filter($request, 'goods_product_id', 'goods_article')
        ->whereHas('goods_article', function ($q) {
            $q->whereNull('archive');
        })
        ->orderBy('moderation', 'desc')
        ->orderBy('sort', 'asc')
        ->paginate(30);

        // -----------------------------------------------------------------------------------------------------------
        // ФОРМИРУЕМ СПИСКИ ДЛЯ ФИЛЬТРА ------------------------------------------------------------------------------
        // -----------------------------------------------------------------------------------------------------------

        $filter = setFilter($this->entity_name, $request, [
            'author',               // Автор записи
            'goods_category',       // Категория товара
            'goods_product',     // Группа продукта
            // 'date_interval',     // Дата обращения
            'booklist'              // Списки пользователя
        ]);

        // Окончание фильтра -----------------------------------------------------------------------------------------

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

        return view('goods.index', compact('goods', 'page_info', 'filter'));
    }

    public function search($text_fragment)
    {

        // Подключение политики
        $this->authorize('index', Goods::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // --------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // --------------------------------------------------------------------------------------------------------------

        $result_search = Goods::with('author', 'company', 'goods_article.goods_product.goods_category')
        ->moderatorLimit($answer)
        ->companiesLimit($answer)
        ->authors($answer)
        ->systemItem($answer) // Фильтр по системным записям
        ->where('name', 'LIKE', '%'.$text_fragment.'%')
        ->whereNull('archive')
        ->orderBy('moderation', 'desc')
        ->orderBy('sort', 'asc')
        ->get();

        if ($result_search->count()) {

            $entity_name = $this->entity_name;

            return view('includes.search', compact('result_search', 'entity_name'));
        } else {

            return view('includes.search');
        }
    }

    public function create(Request $request)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Goods::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_goods_categories = operator_right('goods_categories', false, 'index');

        // Главный запрос
        $goods_categories = GoodsCategory::withCount('goods_products')
        ->with('goods_products')
        ->moderatorLimit($answer_goods_categories)
        ->companiesLimit($answer_goods_categories)
        ->authors($answer_goods_categories)
        ->systemItem($answer_goods_categories) // Фильтр по системным записям
        ->orderBy('sort', 'asc')
        ->get();

        if($goods_categories->count() < 1){

            // Описание ошибки
            $ajax_error = [];
            $ajax_error['title'] = "Обратите внимание!"; // Верхняя часть модалки
            $ajax_error['text'] = "Для начала необходимо создать категории товаров. А уже потом будем добавлять товары. Ок?";
            $ajax_error['link'] = "/admin/goods_categories"; // Ссылка на кнопке
            $ajax_error['title_link'] = "Идем в раздел категорий"; // Текст на кнопке

            return view('ajax_error', compact('ajax_error'));
        }

        $goods_products_count = $goods_categories[0]->goods_products_count;
        $parent_id = null;

        if ($request->cookie('conditions') != null) {

            $condition = Cookie::get('conditions');
            if(isset($condition['goods_category'])) {
                $goods_category_id = $condition['goods_category'];

                $goods_category = $goods_categories->find($goods_category_id);
                // dd($goods_category);

                $goods_products_count = $goods_category->goods_products_count;
                $parent_id = $goods_category_id;
                // dd($goods_products_count);
            }
        }

        // Пишем в куку страницу на которой находимся
        // $backlink = url()->previous();
        // Cookie::queue('backlink', $backlink, 1440);

        // Функция отрисовки списка со вложенностью и выбранным родителем (Отдаем: МАССИВ записей, Id родителя записи, параметр блокировки категорий (1 или null), запрет на отображенеи самого элемента в списке (его Id))
        $goods_categories_list = get_select_tree($goods_categories->keyBy('id')->toArray(), $parent_id, null, null);
        // echo $goods_categories_list;

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_units_categories = operator_right('units_categories', false, 'index');

        // Главный запрос
        $units_categories_list = UnitsCategory::moderatorLimit($answer_units_categories)
        ->companiesLimit($answer_units_categories)
        ->authors($answer_units_categories)
        ->systemItem($answer_units_categories) // Фильтр по системным записям
        ->template($answer_units_categories)
        ->orderBy('sort', 'asc')
        ->get()
        ->pluck('name', 'id');
        // dd($units_categories_list);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_units = operator_right('units', false, 'index');

        // Главный запрос
        $units = Unit::where('units_category_id', 6) // Статичная категория с количеством для товаров
        ->orderBy('sort', 'asc')
        ->get();
        // dd($units);

        $unit_abbreviation = $units->where('id', 26)->first()->abbreviation;
        // dd($unit_abbreviation);

        // $units_list = $units->pluck('name', 'id');
        // dd($units_list);

        return view('goods.create', compact('goods_categories_list', 'goods_products_count', 'units_categories_list', 'units', 'unit_abbreviation'));
    }

    public function store(Request $request)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Goods::class);

        // dd($request);

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Смотрим компанию пользователя
        $company_id = $user->company_id;

        // Скрываем бога
        $user_id = hideGod($user);

        $goods_category_id = $request->goods_category_id;

        // Смотрим пришедший режим группы товаров
        switch ($request->mode) {

            case 'mode-default':
            $goods_product = GoodsProduct::firstOrCreate([
                'name' => $request->name,
                'goods_category_id' => $goods_category_id,
                'set_status' => $request->set_status ? 'set' : 'one',
            ], [
                'unit_id' => $request->unit_id,
                'system_item' => $request->system_item ? $request->system_item : null,
                'display' => 1,
                'company_id' => $company_id,
                'author_id' => $user_id
            ]);

            $goods_product_id = $goods_product->id;
            break;

            case 'mode-add':
            $goods_product = GoodsProduct::firstOrCreate([
                'name' => $request->goods_product_name,
                'goods_category_id' => $goods_category_id,
                'set_status' => $request->set_status ? 'set' : 'one',
            ], [
                'unit_id' => $request->unit_id,
                'system_item' => $request->system_item ? $request->system_item : null,
                'display' => 1,
                'company_id' => $company_id,
                'author_id' => $user_id
            ]);

            $goods_product_id = $goods_product->goods_product_id;
            break;

            case 'mode-select':
            $goods_product_id = $request->goods_product_id;
            break;
        }

        $goods_article = new GoodsArticle;
        $goods_article->goods_product_id = $goods_product_id;
        $goods_article->draft = 1;
        $goods_article->company_id = $company_id;
        $goods_article->author_id = $user_id;
        $goods_article->name = $request->name;
        $goods_article->save();

        if ($goods_article) {

            $cur_goods = new Goods;
            $cur_goods->price = $request->price;
            $cur_goods->company_id = $company_id;
            $cur_goods->author_id = $user_id;
            $cur_goods->goods_article()->associate($goods_article);
            $cur_goods->save();

            if ($cur_goods) {

                // Пишем куки состояния
                // $mass = [
                //     'goods_category' => $goods_category_id,
                // ];
                // Cookie::queue('conditions_goods_category', $goods_category_id, 1440);

                // dd($request->quickly);
                if ($request->quickly == 1) {
                    return redirect('/admin/goods');
                } else {
                    return redirect('/admin/goods/'.$cur_goods->id.'/edit');
                }
            } else {
                abort(403, 'Ошибка записи товара');
            }
        } else {
            abort(403, 'Ошибка записи информации товара');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_goods = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // Главный запрос
        $cur_goods = Goods::moderatorLimit($answer_goods)->findOrFail($id);

        // dd($cur_goods);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $cur_goods);

        // Главный запрос
        if ($cur_goods->goods_article->goods_product->set_status == 'one') {

            if ($cur_goods->goods_article->draft == 1) {
                $cur_goods->load([
                    'goods_article' => function ($q) {
                        $q->with([
                            'metrics',
                            'compositions.raws_product' => function ($q) {
                                $q->with('unit', 'raws_category');
                            },
                            'goods_product.goods_category' => function ($query) {
                                $query->with([
                                    'one_metrics' => function ($q) {
                                        $q->with(['property', 'values']);
                                    },
                                    'compositions.raws_product.unit'
                                ]);
                            },
                        ])
                        ->withCount(['metrics', 'compositions']);
                    },
                    'album.photos',
                    'company.manufacturers'
                ]);
            } else {
                $cur_goods->load([
                    'goods_article' => function ($q) {
                        $q->with([
                            'metrics',
                            'compositions.raws_product' => function ($q) {
                                $q->with('unit', 'raws_category');
                            },
                            'goods_product.goods_category'
                        ])
                        ->withCount(['metrics', 'compositions']);
                    },
                    'album.photos'
                ]);
            }
        } else {
            if ($cur_goods->goods_article->draft == 1) {
                $cur_goods->load([
                    'goods_article' => function ($q) {
                        $q->with([
                            'metrics',
                            'set_compositions.goods_product' => function ($q) {
                                $q->with('unit');
                            },
                            'goods_product.goods_category' => function ($query) {
                                $query->with([
                                    'set_metrics' => function ($q) {
                                        $q->with(['property', 'values']);
                                    }
                                ]);
                            },
                        ])
                        ->withCount(['metrics', 'set_compositions']);
                    },
                    'album.photos',
                    'company.manufacturers'
                ]);
            } else {
                $cur_goods->load([
                    'goods_article' => function ($q) {
                        $q->with([
                            'metrics',
                            'set_compositions.raws_product' => function ($q) {
                                $q->with('unit', 'raws_category');
                            },
                            'goods_product.goods_category'
                        ])
                        ->withCount(['metrics', 'set_compositions']);
                    },
                    'album.photos'
                ]);
            }
        }

        // dd($cur_goods);

        // -- TODO -- Перенести в запрос --

        // Массив со значениями метрик товара
        if (count($cur_goods->goods_article->metrics)) {
            // dd($cur_goods->metrics);
            $metrics_values = [];
            foreach ($cur_goods->goods_article->metrics->groupBy('id') as $metric) {
                // dd($metric);
                if ((count($metric) == 1) && ($metric->first()->list_type != 'list')) {
                    $metrics_values[$metric->first()->id] = $metric->first()->pivot->value;
                } else {
                    foreach ($metric as $value) {
                        $metrics_values[$metric->first()->id][] = $value->pivot->value;
                    }
                }
            }
        } else {
            $metrics_values = null;
        }
        // dd($metrics_values);

        // Список категорий товаров

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_goods_categories = operator_right('goods_categories', false, 'index');

        // Категории
        $goods_categories = GoodsCategory::moderatorLimit($answer_goods_categories)
        ->companiesLimit($answer_goods_categories)
        ->authors($answer_goods_categories)
        ->systemItem($answer_goods_categories) // Фильтр по системным записям
        ->orderBy('sort', 'asc')
        ->get(['id','name','category_status','parent_id'])
        ->keyBy('id')
        ->toArray();
        // dd($goods_categories);

        // Функция отрисовки списка со вложенностью и выбранным родителем (Отдаем: МАССИВ записей, Id родителя записи, параметр блокировки категорий (1 или null), запрет на отображение самого элемента в списке (его Id))
        $goods_categories_list = get_select_tree($goods_categories, $cur_goods->goods_article->goods_product->goods_category_id, null, null);
        // dd($goods_categories_list);

        // Список групп категории

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_goods_products = operator_right('goods_products', false, 'index');

        // Группы товаров
        $goods_products_list = GoodsProduct::where(['goods_category_id' => $cur_goods->goods_article->goods_product->goods_category_id, 'set_status' => $cur_goods->goods_article->goods_product->set_status])
        ->orderBy('sort', 'asc')
        ->get()
        ->pluck('name', 'id');
        // dd($goods_products_list);

        // Список каталогов

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_catalogs = operator_right('catalogs', false, 'index');

        $catalogs = Catalog::moderatorLimit($answer_catalogs)
        ->companiesLimit($answer_catalogs)
        ->systemItem($answer_catalogs) // Фильтр по системным записям
        ->whereSite_id(2)
        ->get(['id','name','category_status','parent_id'])
        ->keyBy('id')
        ->toArray();
        // dd($catalogs);

        // Функция отрисовки списка со вложенностью и выбранным родителем (Отдаем: МАССИВ записей, Id родителя записи, параметр блокировки категорий (1 или null), запрет на отображенеи самого элемента в списке (его Id))
        $catalogs_tree = get_parents_tree($catalogs);

        // Рекурсивно считываем наш шаблон
        function show_cats($items, $padding, $parents){
            $string = '';
            $padding = $padding;

            foreach($items as $item){
                $string .= tpl_menus($item, $padding, $parents);
            }
            return $string;
        }

        // Функция отрисовки option'ов
        function tpl_menus($item, $padding, $parents) {

            // Выбираем пункт родителя
            $selected = '';
            if (in_array($item['id'], $parents)) {
                $selected = ' selected';
            }

            // отрисовываем option's
            if ($item['category_status'] == 1) {
                $menu = '<option value="'.$item['id'].'" class="first"'.$selected.'>'.$item['name'].'</option>';
            } else {
                $menu = '<option value="'.$item['id'].'"'.$selected.'>'.$padding.' '.$item['name'].'</option>';
            }

            // Добавляем пробелы вложенному элементу
            if (isset($item['children'])) {
                $i = 1;
                for($j = 0; $j < $i; $j++){
                    $padding .= '&nbsp;&nbsp';
                }
                $i++;

                $menu .= show_cats($item['children'], $padding, $parents);
            }
            return $menu;
        }

        $parents = [];
        foreach ($cur_goods->catalogs as $catalog) {
            $parents[] = $catalog->id;
        }
        // dd($parents);

        // Получаем HTML разметку
        $catalogs_list = show_cats($catalogs_tree, '', $parents);

        // Если товар в статусе черновика
        if ($cur_goods->goods_article->draft == 1) {

            // Список производителей
            $manufacturers_list = $cur_goods->company->manufacturers->pluck('name', 'id');
            // dd($manufacturers_list);

            // Статус товара "один"
            if ($cur_goods->goods_article->goods_product->set_status == 'one') {

                // Получаем из сессии необходимые данные (Функция находиться в Helpers)
                $answer_raws_categories = operator_right('raws_categories', false, 'index');
                $answer_raws_products = operator_right('raws_products', false, 'index');
                $answer_raws = operator_right('raws', false, 'index');

                $raws_articles = RawsArticle::with(['raws_product' => function ($q) {
                    $q->with(['raws_category' => function ($q) {
                        $q->select('id', 'name');
                    }])->select('id', 'name', 'raws_category_id');
                }])
                ->select('id', 'name', 'raws_product_id')
                ->whereHas('raws', function ($query) {
                    $query->whereNull('draft');
                })

                // ->withCount('raws_products')
                ->moderatorLimit($answer_raws_categories)
                ->companiesLimit($answer_raws_categories)
                ->authors($answer_raws_categories)
                ->systemItem($answer_raws_categories) // Фильтр по системным записям
                ->get()
                ->keyBy('id')
                ->groupBy('raws_product.raws_category.name');

                $composition_list = [
                    'name' => 'Сырье',
                    'alias' => 'raws',
                    'composition_categories' => $raws_articles,
                ];
            } else {

                // Статус товара "набор"

                // Получаем из сессии необходимые данные (Функция находиться в Helpers)
                $answer_goods_categories = operator_right('goods_categories', false, 'index');
                $answer_goods_products = operator_right('goods_products', false, 'index');
                $answer_goods = operator_right('goods', false, 'index');

                $goods_articles = GoodsArticle::with(['goods_product' => function ($q) {
                    $q->with(['goods_category' => function ($q) {
                        $q->select('id', 'name');
                    }])->select('id', 'name', 'goods_category_id');
                }])
                ->select('id', 'name', 'goods_product_id')
                ->whereHas('goods', function ($query) {
                    $query->whereNull('draft');
                })

                // ->withCount('goods_products')
                ->moderatorLimit($answer_goods_categories)
                ->companiesLimit($answer_goods_categories)
                ->authors($answer_goods_categories)
                ->systemItem($answer_goods_categories) // Фильтр по системным записям
                ->get()
                ->keyBy('id')
                ->groupBy('goods_product.goods_category.name');

                $composition_list = [
                    'name' => 'Товары',
                    'alias' => 'goods',
                    'composition_categories' => $goods_articles,
                ];
            }
            // dd($composition_list);
        }

        // Настройки для фоток

        // Получаем настройки по умолчанию
        $settings = config()->get('settings');
        // dd($settings);

        $get_settings = EntitySetting::where(['entity' => $this->entity_name])->first();

        if ($get_settings){

            if ($get_settings->img_small_width != null) {
                $settings['img_small_width'] = $get_settings->img_small_width;
            }

            if ($get_settings->img_small_height != null) {
                $settings['img_small_height'] = $get_settings->img_small_height;
            }

            if ($get_settings->img_medium_width != null) {
                $settings['img_medium_width'] = $get_settings->img_medium_width;
            }

            if ($get_settings->img_medium_height != null) {
                $settings['img_medium_height'] = $get_settings->img_medium_height;
            }

            if ($get_settings->img_large_width != null) {
                $settings['img_large_width'] = $get_settings->img_large_width;
            }

            if ($get_settings->img_large_height != null) {
                $settings['img_large_height'] = $get_settings->img_large_height;
            }

            if ($get_settings->img_formats != null) {
                $settings['img_formats'] = $get_settings->img_formats;
            }

            if ($get_settings->img_min_width != null) {
                $settings['img_min_width'] = $get_settings->img_min_width;
            }

            if ($get_settings->img_min_height != null) {
                $settings['img_min_height'] = $get_settings->img_min_height;
            }

            if ($get_settings->img_max_size != null) {
                $settings['img_max_size'] = $get_settings->img_max_size;
            }
        }

        // Получаем настройки по умолчанию
        $settings_album = config()->get('settings');
        // dd($settings_album);

        $get_settings = EntitySetting::where(['entity' => 'albums_categories', 'entity_id' => 1])->first();

        if ($get_settings){

            if ($get_settings->img_small_width != null) {
                $settings_album['img_small_width'] = $get_settings->img_small_width;
            }

            if ($get_settings->img_small_height != null) {
                $settings_album['img_small_height'] = $get_settings->img_small_height;
            }

            if ($get_settings->img_medium_width != null) {
                $settings_album['img_medium_width'] = $get_settings->img_medium_width;
            }

            if ($get_settings->img_medium_height != null) {
                $settings_album['img_medium_height'] = $get_settings->img_medium_height;
            }

            if ($get_settings->img_large_width != null) {
                $settings_album['img_large_width'] = $get_settings->img_large_width;
            }

            if ($get_settings->img_large_height != null) {
                $settings_album['img_large_height'] = $get_settings->img_large_height;
            }

            if ($get_settings->img_formats != null) {
                $settings_album['img_formats'] = $get_settings->img_formats;
            }

            if ($get_settings->img_min_width != null) {
                $settings_album['img_min_width'] = $get_settings->img_min_width;
            }

            if ($get_settings->img_min_height != null) {
                $settings_album['img_min_height'] = $get_settings->img_min_height;
            }

            if ($get_settings->img_max_size != null) {
                $settings_album['img_max_size'] = $get_settings->img_max_size;
            }
        }
        // dd($settings_album);

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);
        // dd($page_info);

        return view('goods.edit', compact('cur_goods', 'page_info', 'goods_categories_list', 'goods_products_list', 'manufacturers_list', 'metrics_values', 'settings', 'settings_album', 'composition_list', 'catalogs_list'));
    }

    public function update(Request $request, $id)
    {

        // dd($request);

        // Получаем из сессии необходимые данные (Функция находится в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $cur_goods = Goods::with('goods_article.goods_product')->moderatorLimit($answer)->findOrFail($id);
        // dd($cur_goods);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $cur_goods);

        // Определяем количество метрик и составов
        if (isset($request->metrics)) {
            $metrics_count = count($request->metrics);
        } else {
            $metrics_count = 0;
        }
        // dd($metrics_count);

        if (isset($request->compositions_values)) {
            $compositions_count = count($request->compositions_values);
        } else {
            $compositions_count = 0;
        }
        // dd($compositions_count);

        // Если снят флаг черновика, проверяем на совпадение артикула
        if (empty($request->draft)) {

            // dd($request);

            $check_name = $this->check_coincidence_name($request);
            // dd($check_name);
            if ($check_name) {
                return redirect()->back()->withInput()->withErrors('Такой артикул уже существует других в группах');
            }

            $check_article = $this->check_coincidence_article($request);
            if ($check_article) {
                return redirect()->back()->withInput()->withErrors('Такой артикул уже существует в группе!');
            }

            $goods_article = $cur_goods->goods_article;
            $goods_article->draft = null;
            $goods_article->save();
            // $goods_article = GoodsArticle::where('id', $cur_goods->goods_article_id)->update(['draft' => null]);
        }

        // Если что то не совпало, пишем новый артикул


        // если в черновике поменяли производителя
        if ($cur_goods->goods_article->draft == 1) {
            if ($request->manufacturer_id != $cur_goods->goods_article->manufacturer_id) {
                $goods_article = $cur_goods->goods_article;
                $goods_article->manufacturer_id = $request->manufacturer_id;
                $goods_article->save();
            }
        }


        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Смотрим компанию пользователя
        $company_id = $user->company_id;

        // Скрываем бога
        $user_id = hideGod($user);

        // Если пришла фотография
        if ($request->hasFile('photo')) {

            // Вытаскиваем настройки
            // Вытаскиваем базовые настройки сохранения фото
            $settings = config()->get('settings');

            // Начинаем проверку настроек, от компании до альбома
            // Смотрим общие настройки для сущности
            $get_settings = EntitySetting::where(['entity' => $this->entity_name])->first();

            if($get_settings){

                if ($get_settings->img_small_width != null) {
                    $settings['img_small_width'] = $get_settings->img_small_width;
                }

                if ($get_settings->img_small_height != null) {
                    $settings['img_small_height'] = $get_settings->img_small_height;
                }

                if ($get_settings->img_medium_width != null) {
                    $settings['img_medium_width'] = $get_settings->img_medium_width;
                }

                if ($get_settings->img_medium_height != null) {
                    $settings['img_medium_height'] = $get_settings->img_medium_height;
                }

                if ($get_settings->img_large_width != null) {
                    $settings['img_large_width'] = $get_settings->img_large_width;
                }

                if ($get_settings->img_large_height != null) {
                    $settings['img_large_height'] = $get_settings->img_large_height;
                }

                if ($get_settings->img_formats != null) {
                    $settings['img_formats'] = $get_settings->img_formats;
                }

                if ($get_settings->img_min_width != null) {
                    $settings['img_min_width'] = $get_settings->img_min_width;
                }

                if ($get_settings->img_min_height != null) {
                    $settings['img_min_height'] = $get_settings->img_min_height;
                }

                if ($get_settings->img_max_size != null) {
                    $settings['img_max_size'] = $get_settings->img_max_size;

                }
            }

            $directory = $company_id.'/media/goods/'.$cur_goods->id.'/img/';

            // Отправляем на хелпер request(в нем находится фото и все его параметры, id автора, id сомпании, директорию сохранения, название фото, id (если обновляем)), в ответ придет МАССИВ с записсаным обьектом фото, и результатом записи
            if ($cur_goods->photo_id) {
                $array = save_photo($request, $directory, 'avatar-'.time(), null, $cur_goods->photo_id, $settings);

            } else {
                $array = save_photo($request, $directory, 'avatar-'.time(), null, null, $settings);

            }
            $photo = $array['photo'];

            $cur_goods->photo_id = $photo->id;
        }

        // $cur_goods->name = $request->name;

        // -------------------------------------------------------------------------------------------------
        // ПЕРЕНОС ГРУППЫ ТОВАРА В ДРУГУЮ КАТЕГОРИЮ ПОЛЬЗОВАТЕЛЕМ

        // Получаем выбранную категорию со страницы (то, что указал пользователь)
        $goods_category_id = $request->goods_category_id;

        // Смотрим: была ли она изменена
        if($cur_goods->goods_article->goods_product->goods_category_id != $goods_category_id){

            // Была изменена! Переназначаем категорию группе:
            $item = GoodsProduct::where('id', $cur_goods->goods_article->goods_product_id)->update(['goods_category_id' => $goods_category_id]);
        }

        // -------------------------------------------------------------------------------------------------
        // ПЕРЕНОС ТОВАРА В ДРУГУЮ ГРУППУ ПОЛЬЗОВАТЕЛЕМ
        // Важно! Важно проверить, соответствеут ли группа в которую переноситься товар, метрикам самого товара
        // Если не соответствует - дать отказ. Если соответствует - осуществить перенос

        // Получаем выбранную группу со страницы (то, что указал пользователь)
        $goods_product_id = $request->goods_product_id;

        if ($cur_goods->goods_article->goods_product_id != $goods_product_id ) {

            // Была изменена! Переназначаем категорию группе:
            $item = GoodsArticle::where('id', $cur_goods->goods_article_id)->update(['goods_product_id' => $goods_product_id]);

        }

        // А, пока изменяем без проверки


        // Если нет прав на создание полноценной записи - запись отправляем на модерацию
        if ($answer['automoderate'] == false) {
            $cur_goods->moderation = 1;
        }

        if ($request->portion_status == 1) {
            $cur_goods->portion_status = $request->portion_status;
            $cur_goods->portion_name = $request->portion_name;
            $cur_goods->portion_abbreviation = $request->portion_abbreviation;
            $cur_goods->portion_count = $request->portion_count;
        }



        $cur_goods->description = $request->description;
        $cur_goods->display = $request->display;


        // Названия артикулов
        $cur_goods->manually = $request->manually;
        $cur_goods->external = $request->external;

        // Цены
        $cur_goods->cost = $request->cost;
        $cur_goods->price = $request->price;

        // Общие данные
        $cur_goods->system_item = $request->system_item;
        $cur_goods->company_id = $company_id;
        $cur_goods->editor_id = $user_id;
        $cur_goods->save();

        if ($cur_goods) {

            // Проверяем каталоги
            if (isset($request->catalogs)) {

                $mass = [];
                foreach ($request->catalogs as $catalog) {
                    $mass[$catalog] = ['display' => 1];
                }

                // dd($mass);
                $cur_goods->catalogs()->sync($mass);
            } else {
                $cur_goods->catalogs()->detach();
            }

            // Получаем артикул товара
            $goods_article = $cur_goods->goods_article;

            // dd($request->metrics);
            if (isset($request->metrics)) {

                $goods_article->metrics()->detach();

                $metrics_insert = [];

                foreach ($request->metrics as $metric_id => $values) {
                    foreach ($values as $value) {
                        // dd($value);
                        $goods_article->metrics()->attach([
                            $metric_id => [
                                'value' => $value,
                            ]
                        ]);
                    }
                }
                // dd($metrics_insert);

                // Пишем метрики
                // $cur_goods->metrics_values()->attach($metrics_insert);
            } else {
                $goods_article->metrics()->detach();
            }

            if ($goods_article->goods_product->set_status == 'one') {

                if (isset($request->compositions_values)) {
                    $goods_article->compositions()->detach();
                    $compositions_insert = [];
                    foreach ($request->compositions_values as $composition_id => $value) {
                        $compositions_insert[$composition_id] = [
                            'value' => $value,
                        ];
                    }
                    // dd($compositions_insert);
                    $goods_article->compositions()->attach($compositions_insert);
                } else {
                    $goods_article->compositions()->detach();
                }
            } else {
                if (isset($request->compositions_values)) {
                    $goods_article->set_compositions()->detach();
                    $compositions_insert = [];
                    foreach ($request->compositions_values as $composition_id => $value) {
                        $compositions_insert[$composition_id] = [
                            'value' => $value,
                        ];
                    }
                // dd($compositions_insert);
                    $goods_article->set_compositions()->attach($compositions_insert);
                } else {
                    $goods_article->set_compositions()->detach();
                }
            }

            if ($cur_goods->draft == 1) {

                //     // dd($metrics_insert);
                //     if (isset($request->compositions)) {
                //         $compositions_insert = [];
                //         foreach ($request->compositions as $composition_id => $value) {
                //             // dd($value['value']);
                //             $compositions_insert[$value['cur_goods']]['entity'] = 'goods';
                //             $compositions_insert[$value['cur_goods']]['value'] = $value['count'];
                //         }

                //         // Пишем состав
                //         $cur_goods->compositions_values()->attach($compositions_insert);
                //     }
            }

            if ($goods_article->name != $request->name) {
                $goods_article->name = $request->name;

            }

            $goods_article->manufacturer_id = $request->manufacturer_id;
            $goods_article->metrics_count = $metrics_count;
            $goods_article->compositions_count = $compositions_count;
            $goods_article->save();

            // $goods_article = GoodsArticle::where('id', $cur_goods->goods_article_id)->update(['metrics_count' => $metrics_count, 'compositions_count' => $compositions_count]);

            // Если ли есть
            if ($request->cookie('backlink') != null) {

                $backlink = Cookie::get('backlink');
                return Redirect($backlink);

            }

            return Redirect('/admin/goods');

        } else {
            abort(403, 'Ошибка записи группы товаров');
        }
    }

    public function destroy($id)
    {
        //
    }

    public function archive(Request $request, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, 'delete');

        // ГЛАВНЫЙ ЗАПРОС:
        $cur_goods = Goods::with('goods_article')->moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize('delete', $cur_goods);

        if ($cur_goods) {

            // Получаем пользователя
            $user = $request->user();

            // Скрываем бога
            $user_id = hideGod($user);

            $goods_article = $cur_goods->goods_article;

            $goods_article->editor_id = $user_id;
            $goods_article->archive = 1;
            $goods_article->save();

            if ($goods_article) {
                return Redirect('/admin/goods');
            } else {
                abort(403, 'Ошибка при архивации товара');
            }
        } else {
            abort(403, 'Товар не найден');
        }
    }

    // Отображение на сайте
    public function ajax_sync(Request $request)
    {

        // Описание ошибки
        $ajax_error = [];
        $ajax_error['title'] = "Обратите внимание!"; // Верхняя часть модалки
        $ajax_error['text'] = "Для начала необходимо создать категории товаров. А уже потом будем добавлять товары. Ок?";
        $ajax_error['link'] = "/admin/goods_categories"; // Ссылка на кнопке
        $ajax_error['title_link'] = "Идем в раздел категорий"; // Текст на кнопке

        return view('ajax_error', compact('ajax_error'));
    }

    public function get_inputs(Request $request)
    {

        $product = Product::with('metrics.property', 'compositions.unit')->withCount('metrics', 'compositions')->findOrFail($request->product_id);
        return view('products.cur_goods-form', compact('product'));
        // $product = Product::with('metrics.property', 'compositions.unit')->findOrFail(1);
        // dd($product);
    }

    public function add_photo(Request $request)
    {

        // Подключение политики
        $this->authorize(getmethod('store'), Photo::class);

        if ($request->hasFile('photo')) {
            // Получаем из сессии необходимые данные (Функция находиться в Helpers)
            // $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod('index'));

            // Получаем авторизованного пользователя
            $user = $request->user();

            // Смотрим компанию пользователя
            $company_id = $user->company_id;

            // Скрываем бога
            $user_id = hideGod($user);

           // Иначе переводим заголовок в транслитерацию
            $alias = Transliterate::make($request->name, ['type' => 'url', 'lowercase' => true]);

            $album = Album::where(['company_id' => $company_id, 'name' => $request->name, 'albums_category_id' => 1])->first();

            if ($album) {
                $album_id = $album->id;
            } else {
                $album = new Album;
                $album->company_id = $company_id;
                $album->name = $request->name;
                $album->alias = $alias;
                $album->albums_category_id = 1;
                $album->description = $request->name;
                $album->author_id = $user_id;
                $album->save();

                $album_id = $album->id;
            }

            $cur_goods = Goods::findOrFail($request->id);

            if ($cur_goods->album_id == null) {
                $cur_goods->album_id = $album_id;
                $cur_goods->save();

                if (!$cur_goods) {
                    abort(403, 'Ошибка записи альбома в продукцию');
                }
            }

            // Вытаскиваем настройки
            // Вытаскиваем базовые настройки сохранения фото
            $settings = config()->get('settings');

            // Начинаем проверку настроек, от компании до альбома
            // Смотрим общие настройки для сущности
            $get_settings = EntitySetting::where(['entity' => 'albums_categories', 'entity_id'=> 1])->first();

            if ($get_settings) {

                if ($get_settings->img_small_width != null) {
                    $settings['img_small_width'] = $get_settings->img_small_width;
                }

                if ($get_settings->img_small_height != null) {
                    $settings['img_small_height'] = $get_settings->img_small_height;
                }

                if ($get_settings->img_medium_width != null) {
                    $settings['img_medium_width'] = $get_settings->img_medium_width;
                }

                if ($get_settings->img_medium_height != null) {
                    $settings['img_medium_height'] = $get_settings->img_medium_height;
                }

                if ($get_settings->img_large_width != null) {
                    $settings['img_large_width'] = $get_settings->img_large_width;
                }

                if ($get_settings->img_large_height != null) {
                    $settings['img_large_height'] = $get_settings->img_large_height;
                }

                if ($get_settings->img_formats != null) {
                    $settings['img_formats'] = $get_settings->img_formats;
                }

                if ($get_settings->img_min_width != null) {
                    $settings['img_min_width'] = $get_settings->img_min_width;
                }

                if ($get_settings->img_min_height != null) {
                    $settings['img_min_height'] = $get_settings->img_min_height;
                }

                if ($get_settings->img_max_size != null) {
                    $settings['img_max_size'] = $get_settings->img_max_size;
                }
            }

            $directory = $company_id.'/media/albums/'.$album_id.'/img/';

            // Отправляем на хелпер request(в нем находится фото и все его параметры, id автора, id сомпании, директорию сохранения, название фото, id (если обновляем)), в ответ придет МАССИВ с записсаным обьектом фото, и результатом записи
            $array = save_photo($request, $directory,  $alias.'-'.time(), $album_id, null, $settings);

            $photo = $array['photo'];
            $upload_success = $array['upload_success'];

            $media = new AlbumEntity;
            $media->album_id = $album_id;
            $media->entity_id = $photo->id;
            $media->entity = 'photos';
            $media->save();

            // $check_media = AlbumEntity::where(['album_id' => $album_id, 'entity_id' => $request->id, 'entity' => 'product'])->first();

            // if ($check_media == false) {
            //     $media = new AlbumEntity;
            //     $media->album_id = $album_id;
            //     $media->entity_id = $request->id;
            //     $media->entity = 'product';
            //     $media->save();
            // }

            if ($upload_success) {

                // Переадресовываем на index
                // return redirect()->route('/products/'.$product->id.'/edit', ['photo' => $photo, 'upload_success' => $upload_success]);

                return response()->json($upload_success, 200);
            } else {
                return response()->json('error', 400);
            }

        } else {
            return response()->json('error', 400);
        }
    }

    public function photos(Request $request)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod('index'));

        // ГЛАВНЫЙ ЗАПРОС:
        $cur_goods = Goods::with('album.photos')->moderatorLimit($answer)->findOrFail($request->cur_goods_id);
        // dd($product);

        // Подключение политики
        $this->authorize(getmethod('edit'), $cur_goods);

        return view('goods.photos', compact('cur_goods'));
    }


    // -------------------------------------- Проверки на совпаденеи артикула ----------------------------------------------------

    // Проверка имени по компании
    public function check_coincidence_name($request)
    {

        // Смотрим имя артикула по системе
            // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_goods_articles = operator_right('goods_article', false, 'index');

        $goods_articles = GoodsArticle::moderatorLimit($answer_goods_articles)
        ->companiesLimit($answer_goods_articles)
        ->whereNull('draft')
        ->whereNull('archive')
        ->whereName($request->name)
        ->get(['name', 'goods_product_id']);
            // dd($goods_articles);

        if (count($goods_articles)) {

                // Смотрим группу артикулов
            $diff_count = $goods_articles->where('goods_product_id', '!=', $request->goods_product_id)->count();
                // dd($diff_count);
            if ($diff_count > 0) {

                    // $lol = $goods_articles->where('goods_product_id', '!=', $request->goods_product_id);
                    // dd($lol->implode('name', ', '));

                return true;
            }
        }
    }

    public function check_coincidence_article($request)
    {

        // Определяем количество метрик и составов
        if (isset($request->metrics)) {
            $metrics_count = count($request->metrics);
        } else {
            $metrics_count = 0;
        }
        // dd($metrics_count);

        if (isset($request->compositions_values)) {
            $compositions_count = count($request->compositions_values);
        } else {
            $compositions_count = 0;
        }
        // dd($compositions_count);

        // Вытаскиваем артикулы продукции с нужным нам числом метрик и составов
        $goods_articles = GoodsArticle::with('metrics', 'compositions', 'set_compositions')
        ->where('goods_product_id', $request->goods_product_id)
        ->where(['metrics_count' => $metrics_count, 'compositions_count' => $compositions_count])
        ->whereNull('draft')
        ->whereNull('archive')
        ->get();
        // dd($goods_articles);

        if ($goods_articles) {

            // Создаем массив совпадений
            $coincidence = [];
            // dd($request);

            if (isset($request->manufacturer_id)) {
                $manufacturer_id = $request->manufacturer_id;
            } else {
                $manufacturer_id = null;
            }

            // dd($manufacturer_id);

            // Сравниваем метрики
            foreach ($goods_articles as $goods_article) {
                // foreach ($goods_article->goods as $cur_goods) {
                // dd($goods_articles);

                // Формируем массив метрик артикула
                $metrics_array = [];
                foreach ($goods_article->metrics as $metric) {
                    // dd($metric);
                    $metrics_array[$metric->id][] = $metric->pivot->value;
                }

                // Если значения метрик совпали, создаюм ключ метрик
                if ($metrics_array == $request->metrics) {
                    $coincidence['metrics'] = 1;
                }

                // Формируем массив составов артикула
                $compositions_array = [];
                if ($goods_article->goods_product->set_status == 'one') {
                    foreach ($goods_article->compositions as $composition) {
                        // dd($composition);
                        $compositions_array[$composition->id] = $composition->pivot->value;
                    }
                } else {
                    foreach ($goods_article->set_compositions as $composition) {
                        // dd($composition);
                        $compositions_array[$composition->id] = $composition->pivot->value;
                    }
                }

                if ($compositions_array == $request->compositions_values) {
                    // Если значения метрик совпали, создаюм ключ метрик
                    $coincidence['compositions'] = 1;
                }

                if ($goods_article->manufacturer_id == $manufacturer_id) {
                    // Если значения метрик совпали, создаюм ключ метрик
                    $coincidence['manufacturer'] = 1;
                }
                // }
            }
            // dd($coincidence);
            // Если ключи присутствуют, даем ошибку
            if (isset($coincidence['metrics']) && isset($coincidence['compositions']) && isset($coincidence['manufacturer'])) {

                // dd('ошибка');
                return true;
                // dd('lol');
            }
        }
        // dd($coincidence);
    }

}
