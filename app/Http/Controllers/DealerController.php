<?php

namespace App\Http\Controllers;

// Модели
use App\User;
use App\Client;
use App\Dealer;
use App\Company;
use App\Page;
use App\Sector;
use App\Booklist;
use App\List_item;
use App\Schedule;
use App\Worktime;
use App\Location;
use App\ScheduleEntity;
use App\Country;
use App\ServicesType;
use App\Phone;

// Модели которые отвечают за работу с правами + политики
use App\Policies\CompanyPolicy;
use App\Policies\DealerPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

// Запросы и их валидация
use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\SupplierRequest;

// Общие классы
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Подрубаем трейт записи и обновления компании
use App\Http\Controllers\Traits\CompanyControllerTrait;

class DealerController extends Controller
{

    // Сущность над которой производит операции контроллер
    protected $entity_name = 'dealers';
    protected $entity_dependence = false;

    // Подключаем трейт записи и обновления компании
    use CompanyControllerTrait;

    public function index(Request $request)
    {

        // $legal_form_id = cleanNameLegalForm('ПАО Шкура');
        // dd($legal_form_id['name']);

        $filter_url = autoFilter($request, $this->entity_name);
        if(($filter_url != null)&&($request->filter != 'active')){return Redirect($filter_url);};

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Dealer::class);

        // Получаем авторизованного пользователя
        $user = $request->user();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // -------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // -------------------------------------------------------------------------------------------------------------

        $dealers = Dealer::with('client.client.main_phones', 'client.client.director', 'client.orders', 'client.client.location')
        ->companiesLimit($answer)
        ->moderatorLimit($answer)
        ->authors($answer)
        ->systemItem($answer)

        // Ограничение: выбираем только клиентов-компании.
        // В случае, если дилером может быть физлицо - необходимо убрать это ограничение.
        ->whereHas('client', function($q){
            return $q->where('client_type', 'App\Company');
        })

        ->filter($request, 'city_id', 'location')
        ->filter($request, 'sector_id')
        ->booklistFilter($request)
        ->orderBy('moderation', 'desc')
        ->orderBy('sort', 'asc')
        ->paginate(30);

        // -----------------------------------------------------------------------------------------------------------
        // ФОРМИРУЕМ СПИСКИ ДЛЯ ФИЛЬТРА ------------------------------------------------------------------------------
        // -----------------------------------------------------------------------------------------------------------

        $filter = setFilter($this->entity_name, $request, [
            'city',                 // Город
            'sector',               // Направление деятельности
            'booklist'              // Списки пользователя
        ]);

        // Окончание фильтра -----------------------------------------------------------------------------------------

        // dd($dealers);

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

        return view('dealers.index', compact('dealers', 'page_info', 'filter', 'user'));
    }

    public function create(Request $request)
    {

        //Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Dealer::class);
        $this->authorize(getmethod(__FUNCTION__), Company::class);

        // Создаем новый экземляр компании
        $dealer = new Dealer;

        // Создаем новый экземляр поставщика
        $company = new Company;

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

        return view('dealers.create', compact('company', 'dealer', 'page_info'));
    }

    public function store(CompanyRequest $request)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Dealer::class);
        $this->authorize(getmethod(__FUNCTION__), Company::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Скрываем бога
        $user_id = hideGod($user);

        $dealer = new Dealer;

        // Отдаем работу по созданию новой компании трейту
        $new_company = $this->createCompany($request);

        $dealer->company_id = $request->user()->company->id;

        $client = new Client;
        $client->client_id = $new_company->id;
        $client->client_type = 'App\Company';
        $client->company_id = $request->user()->company->id;

        // Запись информации по клиенту:
        // ...

        $client->save();

        $dealer->client_id = $client->id;

        // Запись информации по дилеру:
        $dealer->discount = $request->discount;
        $dealer->description = $request->description;
        // ...

        $dealer->save();

        return redirect('/admin/dealers');
    }


    public function show($id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $dealer = Dealer::moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize('view', $dealer);
        return view('dealers.show', compact('dealer'));
    }


    public function edit(Request $request, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $dealer = Dealer::moderatorLimit($answer)
        ->authors($answer)
        ->systemItem($answer)
        ->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $dealer);

        // ПОЛУЧАЕМ КОМПАНИЮ ------------------------------------------------------------------------------------------------
        $company_id = $dealer->client->client->id;
        // dd($company_id);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_company = operator_right('company', false, getmethod(__FUNCTION__));

        $company = Company::with('location.city', 'schedules.worktimes', 'sector', 'services_types')
        ->moderatorLimit($answer)
        ->authors($answer)
        ->systemItem($answer)
        ->findOrFail($company_id);

        $this->authorize(getmethod(__FUNCTION__), $company);

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

        return view('dealers.edit', compact('dealer', 'page_info'));
    }


    public function update(SupplierRequest $request, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $dealer = Dealer::moderatorLimit($answer)
        ->authors($answer)
        ->systemItem($answer)
        ->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $dealer);

        $company_id = $dealer->client->client->id;

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_company = operator_right('companies', false, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $company = Company::with('location', 'schedules.worktimes')->moderatorLimit($answer_company)->findOrFail($company_id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $company);

        // Отдаем работу по редактировнию компании трейту
        $this->updateCompany($request, $dealer->client->client);

        // Обновление информации по дилеру:
        $dealer->discount = $request->discount;
        $dealer->description = $request->description;
        // ...
        
        
        $dealer->save();

        return redirect('/admin/dealers');
    }


    public function destroy(Request $request,$id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $dealer = Dealer::moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $dealer);

        if($dealer) {

            $user = $request->user();

            // Скрываем бога
            $user_id = hideGod($user);
            $dealer->editor_id = $user_id;
            $dealer->save();

            $dealer = Dealer::destroy($id);

            // Удаляем компанию с обновлением
            if($dealer) {
                return redirect('/admin/dealers');

            } else {
                abort(403, 'Ошибка при удалении поставщика');
            }

        } else {

            abort(403, 'Поставщик не найдена');
        }
    }

    public function checkcompany(Request $request)
    {
        $company = Company::where('inn', $request->inn)->first();

        if(!isset($company)) {
            return 0;
        } else {
            return $company->name;};
    }

}
