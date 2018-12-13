<?php

namespace App\Http\Controllers;

// Модели
use App\RawsProduct;
use App\RawsCategory;

// Валидация
use Illuminate\Http\Request;
use App\Http\Requests\RawsProductRequest;

// Общие классы
use Illuminate\Support\Facades\Cookie;

class RawsProductController extends Controller
{

    // Настройки сконтроллера
    public function __construct(RawsProduct $raws_product)
    {
        $this->middleware('auth');
        $this->raws_product = $raws_product;
        $this->class = RawsProduct::class;
        $this->model = 'App\RawsProduct';
        $this->entity_alias = with(new $this->class)->getTable();
        $this->entity_dependence = false;
    }

    public function index(Request $request)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $this->class);

        // Включение контроля активного фильтра
        $filter_url = autoFilter($request, $this->entity_alias);
        if(($filter_url != null)&&($request->filter != 'active')){

            Cookie::queue(Cookie::forget('filter_' . $this->entity_alias));
            return Redirect($filter_url);
        };

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__));

        // -----------------------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // -----------------------------------------------------------------------------------------------------------------------------

        $raws_products = RawsProduct::with(
            'author',
            'company',
            'raws_category',
            'raws_articles'
        )
        ->withCount('raws_articles')
        ->moderatorLimit($answer)
        ->companiesLimit($answer)
        ->authors($answer)
        ->systemItem($answer) // Фильтр по системным записям
        ->booklistFilter($request)
        ->filter($request, 'author_id')
        ->filter($request, 'company_id')
        ->filter($request, 'raws_category_id')
        ->orderBy('moderation', 'desc')
        ->orderBy('sort', 'asc')
        ->paginate(30);


        // -----------------------------------------------------------------------------------------------------------
        // ФОРМИРУЕМ СПИСКИ ДЛЯ ФИЛЬТРА ------------------------------------------------------------------------------
        // -----------------------------------------------------------------------------------------------------------

        $filter = setFilter($this->entity_alias, $request, [
            'author',                   // Автор записи
            'raws_category',            // Категория сырья
            'booklist'                  // Списки пользователя
        ]);

        // Окончание фильтра -----------------------------------------------------------------------------------------

        return view('raws_products.index',[
            'raws_products' => $raws_products,
            'page_info' => pageInfo($this->entity_alias),
            'filter' => $filter,
            'nested' => 'raws_articles_count'
        ]);
    }

    public function create(Request $request)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $this->class);

        return view('raws_products.create', [
            'raws_product' => new $this->class,
            'page_info' => pageInfo($this->entity_alias),
        ]);
    }

    public function store(RawsProductRequest $request)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $this->class);

        // Наполняем сущность данными
        $raws_product = new RawsProduct;
        $raws_product->name = $request->name;
        $raws_product->description = $request->description;
        $raws_product->raws_category_id = $request->raws_category_id;

        $raws_product->system_item = $request->system_item;
        $raws_product->display = $request->display;

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__));

        // Если нет прав на создание полноценной записи - запись отправляем на модерацию
        if ($answer['automoderate'] == false){
            $raws_product->moderation = 1;
        }

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        $raws_product->company_id = $user->company_id;
        $raws_product->author_id = hideGod($user);

        $raws_product->save();

        return redirect()->route('raws_products.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {

       $raws_product = RawsProduct::moderatorLimit(operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__)))
        ->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $raws_product);

        return view('raws_products.edit', [
            'raws_product' => $raws_product,
            'page_info' => pageInfo($this->entity_alias),
        ]);
    }

    public function update(RawsProductRequest $request, $id)
    {

       $raws_product = RawsProduct::moderatorLimit(operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__)))
       ->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $raws_product);

        $raws_product->name = $request->name;
        $raws_product->raws_category_id = $request->raws_category_id;
        $raws_product->description = $request->description;

        // Модерация и системная запись
        $raws_product->system_item = $request->system_item;
        $raws_product->display = $request->display;

        $raws_product->moderation = $request->moderation;

        $raws_product->editor_id = hideGod($request->user());
        $raws_product->save();

        if ($raws_product) {
            return redirect()->route('raws_products.index');
        } else {
            abort(403, 'Ошибка обновления группы товаров');
        }
    }

    public function destroy(Request $request, $id)
    {

        $raws_product = RawsProduct::withCount('raws_articles')
        ->moderatorLimit(operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__)))
        ->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $raws_product);

        $raws_product->editor_id = hideGod($request->user());
        $raws_product->save();

        $raws_product = RawsProduct::destroy($id);

        if ($raws_product) {
            return redirect()->route('raws_products.index');
        } else {
            abort(403, 'Ошибка удаления группы товаров');
        }
    }

    // ------------------------------------- Ajax ------------------------------------------
    public function ajax_change_create_mode(Request $request)
    {
        $mode = $request->mode;
        $raws_category_id = $request->raws_category_id;
        // $mode = 'mode-add';
        // $entity = 'service_categories';

        switch ($mode) {

            case 'mode-default':

            $raws_category = RawsCategory::withCount('raws_products')->find($raws_category_id);
            $raws_products_count = $raws_category->raws_products_count;
            return view('raws.create_modes.mode_default', compact('raws_products_count'));

            break;

            case 'mode-select':

            $raws_products = RawsProduct::with('unit')
            ->where([
                'raws_category_id' => $raws_category_id,
                'set_status' => $request->set_status
            ])
            ->get(['id', 'name', 'unit_id']);
            return view('raws.create_modes.mode_select', compact('raws_products'));

            break;

            case 'mode-add':

            return view('raws.create_modes.mode_add');

            break;

        }
    }

    public function ajax_get_products_list(Request $request)
    {

        $raws_products = RawsProduct::where(['raws_category_id' => $request->raws_category_id, 'set_status' => $request->set_status])
        ->orWhere('id', $request->raws_product_id)
        ->get(['id', 'name']);

        return view('includes.selects.raws_products', ['raws_products' => $raws_products, 'raws_product_id' => $request->raws_product_id, 'set_status' => $request->set_status]);
    }
}
