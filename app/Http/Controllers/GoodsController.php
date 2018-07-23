<?php

namespace App\Http\Controllers;

// Модели
// use App\Article;
use App\Goods;
use App\GoodsCategory;
use App\GoodsMode;
use App\GoodsProduct;
use App\Album;
use App\AlbumEntity;
use App\Photo;

use App\ArticleValue;

// Политика
use App\Policies\GoodsPolicy;
// use App\Policies\AreaPolicy;
// use App\Policies\RegionPolicy;


// Транслитерация
use Transliterate;


use Illuminate\Http\Request;

class GoodsController extends Controller
{

    // Сущность над которой производит операции контроллер
    protected $entity_name = 'goods';
    protected $entity_dependence = false;

    public function index()
    {

        // Подключение политики
        $this->authorize('index', Goods::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));
        // dd($answer);

        // --------------------------------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // --------------------------------------------------------------------------------------------------------------------------------------

        $goods = Goods::with('author', 'company', 'goods_product')
        ->moderatorLimit($answer)
        ->companiesLimit($answer)
        ->authors($answer)
        ->systemItem($answer) // Фильтр по системным записям
        // ->booklistFilter($request)
        // ->filter($request, 'author_id')
        // ->filter($request, 'company_id')
        // ->filter($request, 'products_category_id')
        ->orderBy('moderation', 'desc')
        ->orderBy('sort', 'asc')
        ->paginate(30);

        // dd($products);

        // ---------------------------------------------------------------------------------------------------------------------------------------------
        // ФОРМИРУЕМ СПИСКИ ДЛЯ ФИЛЬТРА ----------------------------------------------------------------------------------------------------------------
        // ---------------------------------------------------------------------------------------------------------------------------------------------

        // $filter_query = Product::with('author', 'company', 'products_category')
        // ->moderatorLimit($answer)
        // ->companiesLimit($answer)
        // ->authors($answer)
        // ->systemItem($answer) // Фильтр по системным записям
        // ->get();
        // // dd($filter_query);

        // $filter['status'] = null;

        // $filter = addFilter($filter, $filter_query, $request, 'Выберите автора:', 'author', 'author_id');
        // $filter = addFilter($filter, $filter_query, $request, 'Выберите компанию:', 'company', 'company_id');
        // $filter = addFilter($filter, $filter_query, $request, 'Выберите категорию:', 'products_category', 'products_category_id');

        // // Добавляем данные по спискам (Требуется на каждом контроллере)
        // $filter = addBooklist($filter, $filter_query, $request, $this->entity_name);

        // ---------------------------------------------------------------------------------------------------------------------------------------------
        // dd($type);
        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

        return view('goods.index', compact('goods', 'page_info'));
    }

    public function create(Request $request)
    {
        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Goods::class);

        $cur_goods = new Goods;

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_goods_categories = operator_right('goods_categories', false, 'index');

        // Главный запрос
        $goods_categories = GoodsCategory::moderatorLimit($answer_goods_categories)
        ->companiesLimit($answer_goods_categories)
        ->authors($answer_goods_categories)
        ->systemItem($answer_goods_categories) // Фильтр по системным записям
        ->orderBy('sort', 'asc')
        ->get(['id','name','category_status','parent_id'])
        ->keyBy('id')
        ->toArray();

        // Функция отрисовки списка со вложенностью и выбранным родителем (Отдаем: МАССИВ записей, Id родителя записи, параметр блокировки категорий (1 или null), запрет на отображенеи самого элемента в списке (его Id))
        $goods_categories_list = get_select_tree($goods_categories, $request->parent_id, null, null);
        // echo $goods_categories_list;


        return view('goods.create', compact('cur_goods', 'goods_categories_list'));
    }

    public function store(Request $request)
    {


        $goods_category_id = $request->goods_category_id;

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Смотрим компанию пользователя
        $company_id = $user->company_id;

        // Скрываем бога
        $user_id = hideGod($user);

        $name = $request->name;

        if ($request->mode == 'mode-add') {

            $goods_product_name = $request->goods_product_name;
            $goods_product = GoodsProduct::where(['name' => $goods_product_name, 'goods_category_id' => $goods_category_id])->first();

            if ($goods_product) {
                $goods_product_id = $goods_product->id;
            } else {

                // Наполняем сущность данными
                $goods_product = new GoodsProduct;

                $goods_product->name = $goods_product_name;
                $goods_product->unit_id = 26;

                $goods_product->goods_category_id = $goods_category_id;

                // Модерация и системная запись
                $goods_product->system_item = $request->system_item;

                $goods_product->display = $request->display;

                $goods_product->company_id = $company_id;
                $goods_product->author_id = $user_id;
                $goods_product->save();

                if ($goods_product) {
                    $goods_product_id = $goods_product->id;
                } else {
                    abort(403, 'Ошибка записи группы товаров');
                }
            }
        } else {

            $goods_product = GoodsProduct::findOrFail($request->goods_product_id);

            $goods_product_name = $goods_product->name;
            $goods_product_id = $goods_product->id;

        }

        $cur_goods = new Goods;

        $cur_goods->template = 1;

        $cur_goods->goods_product_id = $goods_product_id;

        $cur_goods->company_id = $company_id;
        $cur_goods->author_id = $user_id;
        $cur_goods->save();

        $cur_goods->name = $name;
        $cur_goods->save();

        if ($cur_goods) {

            return Redirect('/admin/goods/'.$cur_goods->id.'/edit');

        } else {
            abort(403, 'Ошибка записи артикула услуги');
        }  
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {

        // ГЛАВНЫЙ ЗАПРОС:
        $answer_goods = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // $cur_goods = cur_goods::with(['goods_product.goods_category' => function ($query) {
        //     $query->with(['metrics.property', 'metrics.property', 'compositions' => function ($query) {
        //         $query->with(['goods' => function ($query) {
        //             $query->whereNull('template');
        //         }]);
        //     }])
        //     ->withCount('metrics', 'compositions');
        // }, 'album.photos', 'company.manufacturers', 'metrics_values', 'compositions_values'])->withCount(['metrics_values', 'compositions_values'])->moderatorLimit($answer_goods)->findOrFail($id);

        $cur_goods = Goods::with(['goods_product.goods_category', 'album.photos', 'company.manufacturers'])->moderatorLimit($answer_goods)->findOrFail($id);
        // dd($cur_goods);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $cur_goods);

        $manufacturers_list = $cur_goods->company->manufacturers->pluck('name', 'id');
        // dd($manufacturers_list);

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_goods_categories = operator_right('goods_categories', false, 'index');
        // dd($answer_goods_categories);

        // Категории
        $goods_categories = GoodsCategory::moderatorLimit($answer_goods_categories)
        ->companiesLimit($answer_goods_categories)
        ->authors($answer_goods_categories)
        ->systemItem($answer_goods_categories) // Фильтр по системным записям
        ->orderBy('sort', 'asc')
        ->get(['id','name','category_status','parent_id'])
        ->keyBy('id')
        ->toArray();

        // Функция отрисовки списка со вложенностью и выбранным родителем (Отдаем: МАССИВ записей, Id родителя записи, параметр блокировки категорий (1 или null), запрет на отображение самого элемента в списке (его Id))
        $goods_categories_list = get_select_tree($goods_categories, $cur_goods->goods_product->goods_category_id, null, null);
        // dd($goods_categories_list);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_goods_products = operator_right('goods_products', false, 'index');
        // dd($answer_goods_products);

        // Услуги
        $goods_products_list = GoodsProduct::where('goods_category_id', $cur_goods->goods_product->goods_category_id)
        ->orderBy('sort', 'asc')
        ->get()
        ->pluck('name', 'id');

        // $goods_products_list = goodsProduct::moderatorLimit($answer_goods_products)
        // ->companiesLimit($answer_goods_products)
        // ->authors($answer_goods_products)
        // ->systemItem($answer_goods_products) // Фильтр по системным записям
        // ->where('goods_category_id', $cur_goods->goods_product->goods_category_id)
        // ->orderBy('sort', 'asc')
        // ->get()
        // ->pluck('name', 'id');
        // dd($goods_products_list);

        // dd($type);

        // $goods_category = $cur_goods->goods_product->goods_category;

        // $goods_category_compositions = [];
        // foreach ($goods_category->compositions as $composition) {
        //     $goods_category_compositions[] = $composition->id;
        // }

        // $type = $cur_goods->goods_product->goods_category->type;

        // if ($goods_category->type == 'goods') {
        //     if ($goods_category->status == 'one') {
        //         $type = ['raws'];
        //     } else {
        //         $type = ['goods'];
        //     }
        // }

        // if ($goods_category->type == 'raws') {
        //     $type = [];
        // }

        // if ($goods_category->type == 'goods') {
        //     if ($goods_category->status == 'one') {
        //         $type = ['staff'];
        //     } else {
        //         $type = ['goods'];
        //     }
        // }

        // // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        // $answer_goods_modes = operator_right('goods_modes', false, 'index');

        // $goods_modes = goodsMode::with(['goods_categories' => function ($query) use ($answer_goods_categories) {
        //     $query->with(['goods_products' => function ($query) {
        //         $query->with(['goods' => function ($query) {
        //             $query->whereNull('template');
        //         }]);
        //     }])
        //     ->withCount('goods_products')
        //     ->moderatorLimit($answer_goods_categories)
        //     ->companiesLimit($answer_goods_categories)
        //     ->authors($answer_goods_categories)
        //     ->systemItem($answer_goods_categories); // Фильтр по системным записям 
        // }])
        // ->moderatorLimit($answer_goods_modes)
        // ->companiesLimit($answer_goods_modes)
        // ->authors($answer_goods_modes)
        // ->systemItem($answer_goods_modes) // Фильтр по системным записям
        // ->template($answer_goods_modes)
        // ->orderBy('sort', 'asc')
        // ->get()
        // ->toArray();

        // // dd($goods_modes);

        // $goods_modes_list = [];
        // foreach ($goods_modes as $goods_mode) {
        //     $goods_categories_id = [];
        //     foreach ($goods_mode['goods_categories'] as $goods_cat) {
        //         $goods_categories_id[$goods_cat['id']] = $goods_cat;
        //     }
        //     // Функция отрисовки списка со вложенностью и выбранным родителем (Отдаем: МАССИВ записей, Id родителя записи, параметр блокировки категорий (1 или null), запрет на отображенеи самого элемента в списке (его Id))
        //     $goods_cat_list = get_parents_tree($goods_categories_id, null, null, null);


        //     $goods_modes_list[] = [
        //         'name' => $goods_mode['name'],
        //         'alias' => $goods_mode['alias'],
        //         'goods_categories' => $goods_cat_list,
        //     ];
        // }

        // // dd($goods_modes_list);

        // // dd($product->goods_group->goods_category->type);

        // $metrics_values = $cur_goods->metrics_values->keyBy('id');
        // $compositions_values = $cur_goods->compositions_values->keyBy('product_id');
        // // dd($metrics_values);
        // // dd($compositions_values->where('product_id', 4));

        // $type = $cur_goods->goods_product->goods_category->type;

        // Инфо о странице
        $page_info = pageInfo('goods');

        return view('goods.edit', compact('cur_goods', 'page_info', 'goods_categories_list', 'goods_products_list', 'manufacturers_list', 'type', 'goods_modes_list', 'goods_category_compositions', 'metrics_values', 'compositions_values'));
    }

    public function update(Request $request, $id)
    {

        // dd($request);
        // $metrics_count = count($request->metrics);
        // $compositions_count = count($request->compositions);

        // Если снят флаг черновика
        // if (empty($request->template)) {

        //     // Проверка на наличие артикула
        //     // Вытаскиваем артикулы продукции с нужным нам числом метрик и составов
        //     $goods = cur_goods::with('metrics_values', 'compositions_values')
        //     ->where('product_id', $request->product_id)
        //     ->where(['metrics_count' => $metrics_count, 'compositions_count' => $compositions_count])
        //     ->get();

        //      $goods = cur_goods::with('metrics_values', 'compositions_values')
        //     ->where('product_id', $request->product_id)
        //     ->where(['metrics_count' => $metrics_count, 'compositions_count' => $compositions_count])
        //     ->get();
        //     // dd($goods);

        //     // Создаем массив совпадений
        //     $coincidence = [];

        //     // dd($request->metrics);
        //     $metrics_values = [];
        //     foreach ($request->metrics as $metric_id => $value) {
        //         // dd($value['value']);
        //         $metrics_values[$id][$metric_id] = $value['value'];
        //     }
        //     // dd($metrics_values);

        //     // Сравниваем метрики
        //     $metrics_array = [];
        //     foreach ($goods as $cur_goods) {
        //         foreach ($cur_goods->metrics_values as $metric) {
        //         // dd($metric);
        //             $metrics_array[$cur_goods->id][$metric->id] = $metric->pivot->value;
        //         }
        //     }
        //     // dd($metrics_array);

        //     if ($metrics_values == $metrics_array) {
        //         // Если значения метрик совпали, создаюм ключ метрик
        //         $coincidence['metric'] = 1;
        //     }
        //     // dd($request->compositions);

        //     $compositions_values = [];
        //     foreach ($request->compositions as $composition_id => $value) {
        //         // dd($value['value']);
        //         $compositions_values[$id][$value['cur_goods']] = $value['count'];
        //     }
        //     // dd($compositions_values);

        //     // Сравниваем составы
        //     $compositions_array = [];
        //     foreach ($goods as $cur_goods) {
        //         foreach ($cur_goods->compositions_values as $composition) {
        //             $compositions_array[$cur_goods->id][$composition->id] = $composition->pivot->value;
        //         }
        //     }
        //     // dd($compositions_array);

        //     if ($compositions_values == $compositions_array) {
        //         // Если значения составов совпали, создаюм ключ составов
        //         $coincidence['composition'] = 1;
        //     }

        //     // Проверяем наличие ключей в массиве
        //     if ((array_key_exists('metric', $coincidence) && array_key_exists('composition', $coincidence)) || (array_key_exists('metric', $coincidence) && $cur_goods->product->products_category->compositions) || (array_key_exists('composition', $coincidence) && $cur_goods->product->products_category->metrics)) {
        //         // Если ключи присутствуют, даем ошибку
        //         $result = [
        //             'error_status' => 1,
        //             'error_message' => 'Такой артикул уже существует!',
        //         ];

        //         echo json_encode($result, JSON_UNESCAPED_UNICODE);
        //     }

        //     // dd($coincidence);
        // }

        // Если что то не совпало, пишем новый артикул

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Смотрим компанию пользователя
        $company_id = $user->company_id;

        // Скрываем бога
        $user_id = hideGod($user);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $cur_goods = Goods::moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $cur_goods);

        if ($request->hasFile('photo')) {

            $directory = $company_id.'/media/goods/'.$cur_goods->id.'/img/';
            $name = 'avatar-'.time();

            // Отправляем на хелпер request(в нем находится фото и все его параметры, id автора, id сомпании, директорию сохранения, название фото, id (если обновляем)), в ответ придет МАССИВ с записсаным обьектом фото, и результатом записи
            if ($cur_goods->photo_id) {
                $array = save_photo($request, $user_id, $company_id, $directory, $name, null, $cur_goods->photo_id);

            } else {
                $array = save_photo($request, $user_id, $company_id, $directory, $name);
                
            }
            $photo = $array['photo'];

            $cur_goods->photo_id = $photo->id;
        } 

        

        // Наполняем сущность данными
        $cur_goods->goods_product_id = $request->goods_product_id;
        $cur_goods->name = $request->name;

        $cur_goods->manually = $request->manually;
        // $cur_goods->external = $request->external;
        $cur_goods->cost = $request->cost;
        $cur_goods->price = $request->price;

        $cur_goods->description = $request->description;

        // $cur_goods->manufacturer_id = $request->manufacturer_id;

        // $cur_goods->metrics_count = $metrics_count;
        // $cur_goods->compositions_count = $compositions_count;

        // Если нет прав на создание полноценной записи - запись отправляем на модерацию
        if ($answer['automoderate'] == false) {
            $cur_goods->moderation = 1;
        }

        // Системная запись
        $cur_goods->system_item = $request->system_item;

        $cur_goods->display = $request->display;
        $cur_goods->template = $request->template;
        $cur_goods->company_id = $company_id;
        $cur_goods->author_id = $user_id;
        $cur_goods->save();

        if ($cur_goods) {

            // if ($cur_goods->template == 1) {

            //     if (isset($request->metrics)) {
            //         $metrics_insert = [];
            //         foreach ($request->metrics as $metric_id => $value) {
            //             // dd($value['value']);
            //             $metrics_insert[$metric_id]['entity'] = 'metrics';
            //             $metrics_insert[$metric_id]['value'] = $value['value'];
            //         }

            //         // Пишем метрики
            //         $cur_goods->metrics_values()->attach($metrics_insert);
            //     }

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
            // }

            // $result = [
            //     'error_status' => 0,
            // ];

            // echo json_encode($result, JSON_UNESCAPED_UNICODE);
            return Redirect('/admin/goods');

        } else {
            abort(403, 'Ошибка записи группы товаров');
        }
    }

    public function destroy($id)
    {
        //
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

            $directory = $company_id.'/media/albums/'.$album_id.'/img/';
            $array = save_photo($request, $user_id, $company_id, $directory,  $alias.'-'.time(), $album_id);

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

    // Отображение на сайте
    public function ajax_display(Request $request)
    {

        if ($request->action == 'hide') {
          $display = null;
      } else {
          $display = 1;
      }

      $cur_goods = Goods::findOrFail($request->id);
      $cur_goods->display = $display;
      $cur_goods->save();

      if ($cur_goods) {

          $result = [
            'error_status' => 0,
        ];  
    } else {

      $result = [
        'error_status' => 1,
        'error_message' => 'Ошибка при обновлении отображения на сайте!'
    ];
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
}