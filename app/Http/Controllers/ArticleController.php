<?php

namespace App\Http\Controllers;

// Модели
use App\Article;
use App\Product;
use App\ProductsCategory;
use App\ProductsMode;

use App\ArticleValue;


use Illuminate\Http\Request;

class ArticleController extends Controller
{

    // Сущность над которой производит операции контроллер
    protected $entity_name = 'articles';
    protected $entity_dependence = false;

    public function index()
    {
        //
    }

    public function types(Request $request, $type)
    {

        // Подключение политики
        $this->authorize('index', Article::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));
        // dd($answer);

        // --------------------------------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // --------------------------------------------------------------------------------------------------------------------------------------

        $articles = Article::with('author', 'company', 'product')
        ->whereHas('product', function ($query) use ($type) {
            $query->whereHas('products_category', function ($query) use ($type) {
                $query->where('type', $type);
            });
        })
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
        $page_info = pageInfo('articles/'.$type);

        return view('articles.index', compact('articles', 'page_info', 'product', 'type'));
    }

    public function create(Request $request, $type)
    {
        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Article::class);

        $article = new Article;

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_products_categories = operator_right('products_categories', false, 'index');

        // Главный запрос
        $products_categories = ProductsCategory::moderatorLimit($answer_products_categories)
        ->companiesLimit($answer_products_categories)
        ->authors($answer_products_categories)
        ->systemItem($answer_products_categories) // Фильтр по системным записям
        ->where('type', $type)
        ->orderBy('sort', 'asc')
        ->get(['id','name','category_status','parent_id'])
        ->keyBy('id')
        ->toArray();

        // Функция отрисовки списка со вложенностью и выбранным родителем (Отдаем: МАССИВ записей, Id родителя записи, параметр блокировки категорий (1 или null), запрет на отображенеи самого элемента в списке (его Id))
        $products_categories_list = get_select_tree($products_categories, $request->parent_id, null, null);
        // echo $products_categories_list;


        return view('articles.create', compact('article', 'products_categories_list', 'type'));
    }

    public function store(Request $request)
    {
        $products_category_id = $request->products_category_id;

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Смотрим компанию пользователя
        $company_id = $user->company_id;

        // Скрываем бога
        $user_id = hideGod($user);

        if ($request->mode == 'mode-add') {

            $name = $request->name;

            $product = Product::where(['name' => $name, 'products_category_id' => $products_category_id])->first();

            if ($product) {
                $product_id = $product->id;
            } else {

                // Наполняем сущность данными
                $product = new Product;

                $product->name = $name;
                $product->unit_id = $request->unit_id;

                $product->products_category_id = $products_category_id;

                // Модерация и системная запись
                $product->system_item = $request->system_item;

                $product->display = $request->display;

                $product->company_id = $company_id;
                $product->author_id = $user_id;
                $product->save();

                if ($product) {
                    $product_id = $product->id;
                } else {
                    abort(403, 'Ошибка записи группы товаров');
                }
            }
        } else {

            $product = Product::findOrFail($request->product_id);

            $name = $product->name;
            $product_id = $product->id;
        }

        $article = new Article;

        $article->template = 1;

        $article->product_id = $product_id;

        $article->company_id = $company_id;
        $article->author_id = $user_id;
        $article->save();

        $article->name = $name.' ('. $article->id .')';
        $article->save();

        if ($article) {

            return Redirect('/articles/'.$article->id.'/edit');
        } else {
            abort(403, 'Ошибка записи артикула');
        }  
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        // ГЛАВНЫЙ ЗАПРОС:
        $answer_articles = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        $article = Article::with(['product.products_category' => function ($query) {
            $query->with(['metrics.property', 'compositions' => function ($query) {
                $query->with(['articles' => function ($query) {
                    $query->whereNull('template');
                }]);
            }])
            ->withCount('metrics', 'compositions');
        }, 'album.photos', 'company.manufacturers', 'metrics_values', 'compositions_values'])->withCount(['metrics_values', 'compositions_values'])->moderatorLimit($answer_articles)->findOrFail($id);
        // dd($article->metrics_values);

        $manufacturers_list = $article->company->manufacturers->pluck('name', 'id');
        // dd($manufacturers_list);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $article);

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_products_categories = operator_right('products_categories', false, 'index');

        // Категории
        $products_categories = ProductsCategory::moderatorLimit($answer_products_categories)
        ->companiesLimit($answer_products_categories)
        ->authors($answer_products_categories)
        ->systemItem($answer_products_categories) // Фильтр по системным записям
        ->whereType($article->product->products_category->type)
        ->orderBy('sort', 'asc')
        ->get(['id','name','category_status','parent_id'])
        ->keyBy('id')
        ->toArray();

        // Функция отрисовки списка со вложенностью и выбранным родителем (Отдаем: МАССИВ записей, Id родителя записи, параметр блокировки категорий (1 или null), запрет на отображение самого элемента в списке (его Id))
        $products_categories_list = get_select_tree($products_categories, $article->product->products_category_id, null, null);
        // dd($products_categories_list);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_products = operator_right($this->entity_name, $this->entity_dependence, 'index');

        // Категории
        $products_list = Product::moderatorLimit($answer_products)
        ->companiesLimit($answer_products)
        ->authors($answer_products)
        ->systemItem($answer_products) // Фильтр по системным записям
        ->where('products_category_id', $article->product->products_category_id)
        ->orderBy('sort', 'asc')
        ->get()
        ->pluck('name', 'id');
        // dd($products_list);

        // dd($type);

        $products_category = $article->product->products_category;

        $products_category_compositions = [];
        foreach ($products_category->compositions as $composition) {
            $products_category_compositions[] = $composition->id;
        }

        $type = $article->product->products_category->type;

        if ($products_category->type == 'goods') {
            if ($products_category->status == 'one') {
                $type = ['raws'];
            } else {
                $type = ['goods'];
            }
        }

        if ($products_category->type == 'raws') {
            $type = [];
        }

        if ($products_category->type == 'services') {
            if ($products_category->status == 'one') {
                $type = ['staff'];
            } else {
                $type = ['services'];
            }
        }

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_products_modes = operator_right('products_modes', false, 'index');

        $products_modes = ProductsMode::with(['products_categories' => function ($query) use ($answer_products_categories) {
            $query->with(['products' => function ($query) {
                $query->with(['articles' => function ($query) {
                    $query->whereNull('template');
                }]);
            }])
            ->withCount('products')
            ->moderatorLimit($answer_products_categories)
            ->companiesLimit($answer_products_categories)
            ->authors($answer_products_categories)
            ->systemItem($answer_products_categories); // Фильтр по системным записям 
        }])
        ->moderatorLimit($answer_products_modes)
        ->companiesLimit($answer_products_modes)
        ->authors($answer_products_modes)
        ->systemItem($answer_products_modes) // Фильтр по системным записям
        ->template($answer_products_modes)
        ->whereIn('type', $type)
        ->orderBy('sort', 'asc')
        ->get()
        ->toArray();

        // dd($products_modes);

        $products_modes_list = [];
        foreach ($products_modes as $products_mode) {
            $products_categories_id = [];
            foreach ($products_mode['products_categories'] as $products_cat) {
                $products_categories_id[$products_cat['id']] = $products_cat;
            }
            // Функция отрисовки списка со вложенностью и выбранным родителем (Отдаем: МАССИВ записей, Id родителя записи, параметр блокировки категорий (1 или null), запрет на отображенеи самого элемента в списке (его Id))
            $products_cat_list = get_parents_tree($products_categories_id, null, null, null);


            $products_modes_list[] = [
                'name' => $products_mode['name'],
                'alias' => $products_mode['alias'],
                'products_categories' => $products_cat_list,
            ];
        }

        // dd($products_modes_list);

        // dd($product->products_group->products_category->type);

        $type = $article->product->products_category->type;

        // Инфо о странице
        $page_info = pageInfo('articles/'.$article->product->products_category->type);

        return view('articles.edit', compact('article', 'page_info', 'products_categories_list', 'products_list', 'manufacturers_list', 'type', 'products_modes_list', 'products_category_compositions'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);


        

        // $product_id = $request->product_id;

        // // Вытаскиваем продукт
        // $product = Product::with('metrics', 'compositions')->findOrFail($product_id);
        // // dd($product);

        // // Формируем массивы метрик
        // $metrics = [];
        

        // foreach ($product->metrics as $metric) {

            // $input = 'metrics-'.$metric->id;

        $metrics_values = [];

        foreach ($request->metrics as $metric_id => $value) {
                // dd($value['value']);
            $metrics_values[$metric_id]['entity'] = 'metrics';
            $metrics_values[$metric_id]['value'] = $value['value'];
        }


        $compositions_values = [];

        foreach ($request->compositions as $composition_id => $value) {
                // dd($value['value']);
            $compositions_values[$value['article']]['entity'] = 'articles';
            $compositions_values[$value['article']]['value'] = $value['count'];
        }

            // foreach ($request->$input as $value) {
            //     $metrics[$metric->id][] = [
            //         'entity' => 'metrics',
            //         'value' => $value,
            //     ];
            // }

        // }
        // $metrics_count = count($metrics);

        // dd($metrics_values);
        // dd($metrics);
        // dd($metrics_values);

        // // Формируем массивы составов
        // $compositions = [];
        // $compositions_values = [];

        // foreach ($product->compositions as $composition) {

        //     $input = 'compositions-'.$composition->id;

        //     $compositions_values[$composition->id] = $request->$input;

        //     $compositions[$composition->id] = [
        //         'entity' => 'compositions',
        //         'value' => $request->$input,
        //     ];
        // }
        // $compositions_count = count($compositions);
        // // dd($compositions_values);

        // // Проверка на наличие артикула

        // // Вытаскиваем артикулы продукции с нужным нам числом метрик и составов
        // $articles = Article::with('metrics.values', 'compositions')
        // ->where('product_id', $product_id)
        // ->where(['metrics_count' => $metrics_count, 'compositions_count' => $compositions_count])
        // ->get();
        // // dd($articles);

        // // Создаем массив совпадений
        // $coincidence = [];

        // // Сравниваем метрики
        // $metrics_array = [];
        // foreach ($articles as $article) {
        //     foreach ($article->metrics as $metric) {

        //         $metrics_array[$article->id][$metric->id][] = $metric->pivot->value;

        //     }
        // }
        // // dd($metrics_array);
        // // dd($metrics_values);

        // foreach ($metrics_array as $item) {
        //     // dd($metrics_values);
        //     if ($metrics_values == $item) {

        //         // Если значения метрик совпали, создаюм ключ метрик
        //         $coincidence['metric'] = 1;
        //     }
        // }

        // // Сравниваем составы
        // $compositions_array = [];
        // foreach ($articles as $article) {
        //     foreach ($article->compositions as $composition) {
        //         $compositions_array[$article->id][$composition->id] = $composition->pivot->value;
        //     }
        // }
        // // dd($compositions_array);
        // // dd($compositions_values);

        // foreach ($compositions_array as $item) {
        //     if ($compositions_values == $item) {

        //         // Если значения составов совпали, создаюм ключ составов
        //         $coincidence['composition'] = 1;
        //     }
        // }

        // // Проверяем наличие ключей в массиве
        // if ((array_key_exists('metric', $coincidence)&&array_key_exists('composition', $coincidence))||(array_key_exists('metric', $coincidence)&&$product->compositions)||(array_key_exists('composition', $coincidence)&&$product->metrics)) {

        //     // Если ключи присутствуют, даем ошибку
        //     $result = [
        //         'error_status' => 1,
        //         'error_message' => 'Такой артикул уже существует!',
        //     ];

        //     echo json_encode($result, JSON_UNESCAPED_UNICODE);
        // } else {

        // Если что то не совпало, пишем новый артикул

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        // $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Смотрим компанию пользователя
        $company_id = $user->company_id;

        // Скрываем бога
        $user_id = hideGod($user);

        // ГЛАВНЫЙ ЗАПРОС:
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        $article = Article::moderatorLimit($answer)->findOrFail($id);
        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $article);

        // Наполняем сущность данными
        $article->product_id = $request->product_id;
        $article->name = $request->name;
        $article->external = $request->external;
        $article->cost = $request->cost;
        $article->price = $request->price;

        $article->manufacturer_id = $request->manufacturer_id;
        
        


        // $article->metrics_count = $metrics_count;
        // $article->compositions_count = $compositions_count;

            // Если нет прав на создание полноценной записи - запись отправляем на модерацию
        if ($answer['automoderate'] == false) {
            $article->moderation = 1;
        }

        // Системная запись
        $article->system_item = $request->system_item;

        $article->display = $request->display;
        $article->template = $request->template;

        $article->company_id = $company_id;
        $article->author_id = $user_id;
        $article->save();

        if ($article) {

                // dd($metrics);

            // $metrics_insert = [];
            // foreach ($metrics as $key => $metric) {

            //     foreach ($metric as $values) {
            //         $values['article_id'] = $article->id;
            //         $values['entity_id'] = $key;
            //         $metrics_insert[] = $values;
            //     }
            // }
            //     // dd($metrics_insert);


            // $article_values = ArticleValue::insert($metrics_insert);

            // Пишем метрики
            $article->metrics_values()->detach();
            $article->metrics_values()->attach($metrics_values);

            // Пишем состав
            $article->compositions_values()->detach();
            $article->compositions_values()->attach($compositions_values);

            return Redirect('/articles/'.$article->product->products_category->type);
            // }
        }
    }

    public function destroy($id)
    {
        //
    }

    public function get_inputs(Request $request)
    {

        $product = Product::with('metrics.property', 'compositions.unit')->withCount('metrics', 'compositions')->findOrFail($request->product_id);
        return view('products.article-form', compact('product'));

         // $product = Product::with('metrics.property', 'compositions.unit')->findOrFail(1);
        // dd($product);


    }
}
