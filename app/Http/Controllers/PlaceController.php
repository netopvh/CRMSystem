<?php

namespace App\Http\Controllers;

// Модели
use App\Place;
use App\Country;
use App\PlacesType;
use App\User;
use App\Page;
use App\Folder;
use App\Booklist;
use App\List_item;
use App\Worktime;
use App\Location;
use App\ScheduleEntity;


// Модели которые отвечают за работу с правами + политики
use App\Policies\PlacePolicy;
use App\Policies\PlacesTypePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

// Запросы и их валидация
use Illuminate\Http\Request;
use App\Http\Requests\PlaceRequest;

// Прочие необходимые классы
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

// use Illuminate\Support\Facades\Storage;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{

    // Сущность над которой производит операции контроллер
    protected $entity_name = 'places';
    protected $entity_dependence = false;

    public function index(Request $request)
    {

        // Включение контроля активного фильтра 
        $filter_url = autoFilter($request, $this->entity_name);
        if(($filter_url != null)&&($request->filter != 'active')){return Redirect($filter_url);};

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Place::class);

        // Получаем авторизованного пользователя
        $user = $request->user();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // -------------------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // -------------------------------------------------------------------------------------------------------------------------

        $places = Place::moderatorLimit($answer)
        ->filter($request, 'places_type_id', 'places_types')
        ->booklistFilter($request)
        ->orderBy('moderation', 'desc')
        ->orderBy('sort', 'asc')
        ->paginate(30);


        // -----------------------------------------------------------------------------------------------------------
        // ФОРМИРУЕМ СПИСКИ ДЛЯ ФИЛЬТРА ------------------------------------------------------------------------------
        // -----------------------------------------------------------------------------------------------------------

        $filter = setFilter($this->entity_name, $request, [
            'places_type',          // Должность
            'booklist'              // Списки пользователя
        ]);

        // Окончание фильтра -----------------------------------------------------------------------------------------


        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

        return view('places.index', compact('places', 'page_info', 'filter', 'user'));
    }

    public function create(Request $request)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Place::class);

        $place = new Place;

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

        // // Подключение политики
        // $this->authorize(getmethod('index'), PlacesType::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_places_types = operator_right('places_types', 'false', 'index');

        // Список типов помещений
        $places_types_query = PlacesType::get();

        // Контейнер для checkbox'а - инициируем
        $checkboxer['status'] = null;
        $checkboxer['entity_name'] = $this->entity_name;

        // Настраиваем checkboxer
        $places_types_checkboxer = addFilter(

            $checkboxer,                // Контейнер для checkbox'а
            $places_types_query,        // Коллекция которая будет взята
            $request,
            'Тип помещения',            // Название чекбокса для пользователя в форме
            'places_types',             // Имя checkboxa для системы
            'id',                       // Поле записи которую ищем
            'places_types', 
            'internal-self-one',        // Режим выборки через связи
            'checkboxer'                // Режим: checkboxer или filter

        );

        // Получаем список стран
        $countries_list = Country::get()->pluck('name', 'id');

        return view('places.create', compact('place', 'page_info', 'places_types_checkboxer', 'countries_list'));
    }

    public function store(Request $request)
    {
        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Place::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // Получаем авторизованного пользователя
        $user = $request->user();
        $user_id = $user->id;

        $place = new Place;
        $place->name = $request->name;
        $place->description = $request->description;
        $place->square = $request->square;
        
        // dd($request);

        // Добавляем локацию
        $location_id = create_location($request);
        $place->location_id = $location_id;

        if($user->company_id != null){
            $place->company_id = $user->company_id;
        } else {
            $place->company_id = null;
        };

        $place->author_id = $user->id;

        // Если нет прав на создание полноценной записи - запись отправляем на модерацию
        if($answer['automoderate'] == false){
            $place->moderation = 1;
        };

        $place->save();

        // Если запись удачна - будем записывать связи
        if($place){

            // Записываем связи: id-шники в таблицу Rooms
            if(isset($request->places_types_id)){
                
                $result = $place->places_types()->sync($request->places_types_id);               
            } else {
                $result = $place->places_types()->detach(); 
            };

        } else {
            abort(403, 'Ошибка записи помещения');
        };

        return redirect('/admin/places');
    }


    public function show($id)
    {

        // Получаем авторизованного пользователя
        $user = $request->user();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $place = Place::moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize('update', $role);

        $role->name = $request->name;
        $role->description = $request->description;

        $role->save();

        return redirect('/admin/roles');

    }


    public function edit(Request $request, $id)
    {
        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $place = Place::with('places_types')->moderatorLimit($answer)->findOrFail($id);
        // dd($place->places_types);

        // Список типов помещений
        $places_types_query = PlacesType::get();

        $places_types = [];

        foreach ($place->places_types as $place_type){
            $places_types[] = $place_type->id;
        }

        // Имя столбца
        $column = 'places_types_id';
        $request[$column] = $places_types;

        // Контейнер для checkbox'а - инициируем
        $checkboxer['status'] = null;
        $checkboxer['entity_name'] = $this->entity_name;


        // Настраиваем checkboxer
        $places_types_checkboxer = addFilter(

            $checkboxer,                // Контейнер для checkbox'а
            $places_types_query,        // Коллекция которая будет взята
            $request,
            'Тип помещения',            // Название чекбокса для пользователя в форме
            'places_types',             // Имя checkboxa для системы
            'id',                       // Поле записи которую ищем
            'places_types', 
            'internal-self-one'        // Режим выборки через связи
            // 'checkboxer'                // Режим: checkboxer или filter

        );

        // dd($places_types_checkboxer);

        // Получаем список стран
        $countries_list = Country::get()->pluck('name', 'id');

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $place);

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

        return view('places.edit', compact('place', 'page_info', 'places_types_checkboxer', 'countries_list'));
    }


    public function update(PlaceRequest $request, $id)
    {

        // Получаем авторизованного пользователя
        $user = $request->user();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));


        // ГЛАВНЫЙ ЗАПРОС:
        $place = Place::with('location')->moderatorLimit($answer)->findOrFail($id);

        // dd($place);

        // Подключение политики
        $this->authorize('update', $place);

        // Обновляем локацию
        $location = update_location($request, $place);
        // Если пришла другая локация, то переписываем
        if ($place->location_id != $location->id) {
            $place->location_id = $location->id;
        }

        $place->name = $request->name;
        $place->description = $request->description;
        $place->square = $request->square;

        $place->save();

        // Если запись удачна - будем записывать связи
        if($place){

            // Записываем связи: id-шники в таблицу Rooms
            $result = $place->places_types()->sync($request->places_types_id);

        } else {
            abort(403, 'Ошибка записи помещения');
        };

        return redirect('/admin/places');

    }


    public function destroy(Request $request, $id)
    {
        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $place = Place::moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $place);

        // Удаляем пользователя с обновлением
        $place = Place::destroy($id);

        if($place) {return redirect('/admin/places');} else {abort(403,'Что-то пошло не так!');};
    }

}
