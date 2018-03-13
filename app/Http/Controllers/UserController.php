<?php

namespace App\Http\Controllers;

use App\User;
use App\Position;
use App\Staffer;
use App\RoleUser;
use App\Http\Controllers\Session;

// Модели которые отвечают за работу с правами + политики
use App\Role;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

// Запросы и их валидация
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

// Прочие необходимые классы
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    // Сущность над которой производит операции контроллер
    protected $entity_name = 'users';
    protected $entity_dependence = true;

    public function index(Request $request)
    {
        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), User::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));
        // dd($answer);

        // ---------------------------------------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // ---------------------------------------------------------------------------------------------------------------------------------------------

        $users = User::with('roles', 'staff', 'staff.position')  
        ->moderatorLimit($answer)
        ->companiesLimit($answer)
        ->filials($answer) // $filials должна существовать только для зависимых от филиала, иначе $filials должна null
        ->authors($answer)
        ->systemItem($answer) // Фильтр по системным записям              
        ->orWhere('id', $request->user()->id) // Только для сущности USERS
        ->userFilter($request)
        ->orderBy('moderation', 'desc')
        ->paginate(30);

        // ---------------------------------------------------------------------------------------------------------------------------------------------
        // ФОРМИРУЕМ СПИСКИ ДЛЯ ФИЛЬТРА ----------------------------------------------------------------------------------------------------------------
        // ---------------------------------------------------------------------------------------------------------------------------------------------

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_cities = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));
        $filter_query = User::with('city')->moderatorLimit($answer_cities)->get();
        $filter = getFilterUser($filter_query);

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

	    return view('users.index', compact('users', 'page_info', 'filter', 'user'));
	}

    public function create(Request $request)
    {

        $user_auth = $request->user();

        // Подключение политики
        $this->authorize(__FUNCTION__, User::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // Функция из Helper отдает массив со списками для SELECT
        $departments_list = getLS('users', 'view', 'departments');
        $filials_list = getLS('users', 'view', 'departments');

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_roles = operator_right('roles', false, 'index');
        $roles_list = Role::whereCompany_id($user_auth->company_id)->moderatorLimit($answer_roles)->pluck('role_name', 'id');

    	$user = new User;
        $roles = new Role;

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

    	return view('users.create', compact('user', 'roles', 'filials_list', 'departments_list', 'roles_list', 'page_info'));
    }

    public function store(UserRequest $request)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), User::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // Получаем данные для авторизованного пользователя
        $user_auth = $request->user();
        $user_auth_id = $user_auth->id;
        $user_status = $user_auth->god;
        $company_id = $user_auth->company_id;
        $filial_id = $request->filial_id;

        // ПОЛУЧЕНИЕ И СОХРАНЕНИЕ ДАННЫХ
        $user = new User;

        $user->login = $request->login;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->nickname = $request->nickname;

        $user->first_name =   $request->first_name;
        $user->second_name = $request->second_name;
        $user->patronymic = $request->patronymic;
        $user->sex = $request->sex;
        $user->birthday = $request->birthday;

        $user->phone = cleanPhone($request->phone);

        if(($request->extra_phone != Null)&&($request->extra_phone != "")){
            $user->extra_phone = cleanPhone($request->extra_phone);
        };

        $user->telegram_id = $request->telegram_id;
        $user->city_id = $request->city_id;
        $user->address = $request->address;

        $user->orgform_status = $request->orgform_status;
        $user->user_inn = $request->inn;

        $user->passport_address = $request->passport_address;
        $user->passport_number = $request->passport_number;
        $user->passport_released = $request->passport_released;
        $user->passport_date = $request->passport_date;

        $user->user_type = $request->user_type;
        $user->lead_id = $request->lead_id;
        $user->employee_id = $request->employee_id;
        $user->access_block = $request->access_block;

        $user->author_id = $user_auth_id;

        // Если нет прав на создание полноценной записи - запись отправляем на модерацию
        if($answer['automoderate'] == false){
            $user->moderation = 1;
        };

        // Пишем ID компании авторизованного пользователя
        if($company_id == null){abort(403, 'Необходимо авторизоваться под компанией');};
        $user->company_id = $company_id;

        // Пишем ID филиала авторизованного пользователя
        if($filial_id == null){abort(403, 'Операция невозможна. Вы не являетесь сотрудником!');};
        $user->filial_id = $filial_id;


        // Создаем папку в файловой системе
        $link_for_folder = 'public/companies/' . $company_id . '/'. $filial_id . '/users/' . $user->id . 'avatars';
        Storage::makeDirectory($link_for_folder);

        $link_for_folder = 'public/companies/' . $company_id . '/'. $filial_id . '/users/' . $user->id . 'photos';
        Storage::makeDirectory($link_for_folder);

        $link_for_folder = 'public/companies/' . $company_id . '/'. $filial_id . '/users/' . $user->id . 'video';
        Storage::makeDirectory($link_for_folder);

        $link_for_folder = 'public/companies/' . $company_id . '/'. $filial_id . '/users/' . $user->id . 'documents';
        Storage::makeDirectory($link_for_folder);


        $user->save();
        return redirect('users');
    }


    public function update(UserRequest $request, $id)
    {

        // Получаем авторизованного пользователя
        $user_auth = $request->user();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $user = User::moderatorLimit($answer)->findOrFail($id);

        $filial_id = $request->filial_id;

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $user);

    	$user->login = $request->login;
    	$user->email = $request->email;
    	$user->password = bcrypt($request->password);
    	$user->nickname = $request->nickname;

    	$user->first_name = $request->first_name;
    	$user->second_name = $request->second_name;
    	$user->patronymic = $request->patronymic;
		$user->sex = $request->sex;
	 	$user->birthday = $request->birthday;

    	$user->phone = cleanPhone($request->phone);

    	if(($request->extra_phone != NULL)&&($request->extra_phone != "")){
    		$user->extra_phone = cleanPhone($request->extra_phone);
    	} else {$user->extra_phone = NULL;};

    	$user->telegram_id = $request->telegram_id;
    	$user->city_id = $request->city_id;
    	$user->address = $request->address;

    	$user->orgform_status = $request->orgform_status;

    	$user->user_inn = $request->inn;

    	$user->passport_address = $request->passport_address;
    	$user->passport_number = $request->passport_number;
    	$user->passport_released = $request->passport_released;
    	$user->passport_date = $request->passport_date;

    	$user->user_type = $request->user_type;
        
    	$user->lead_id = $request->lead_id;
    	$user->employee_id = $request->employee_id;
    	$user->access_block = $request->access_block;

        $user->filial_id = $request->filial_id;

        // Модерируем (Временно)
        if($answer['automoderate']){$user->moderation = null;};

		$user->save();
		return redirect('users');
    }

    public function show(Request $request, $id)
    {

        // ГЛАВНЫЙ ЗАПРОС:
        $user = User::moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $user);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // Функция из Helper отдает массив со списками для SELECT
        $departments_list = getLS('users', 'view', 'departments');
        $filials_list = getLS('users', 'view', 'filials');

        $role = new Role;
        $role_users = RoleUser::with('role', 'department', 'position')->whereUser_id($user->id)->get();

        $answer_roles = operator_right('roles', false, 'index');

        $roles_list = Role::moderatorLimit($answer_roles)
        ->companiesLimit($answer_roles)
        ->filials($answer_roles) // $filials должна существовать только для зависимых от филиала, иначе $filials должна null
        ->authors($answer_roles)
        ->systemItem($answer_roles) // Фильтр по системным записям 
        ->pluck('role_name', 'id');

        return view('users.edit', compact('user', 'role', 'role_users', 'roles_list', 'departments_list', 'filials_list'));
    }

    public function edit(Request $request, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $user = User::with('city')->moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $user);

        // Функция из Helper отдает массив со списками для SELECT
        $departments_list = getLS('users', 'index', 'departments');
        $filials_list = getLS('users', 'index', 'filials');

        $role = new Role;
        $role_users = RoleUser::with('role', 'department', 'position')->whereUser_id($user->id)->get();

        $answer_roles = operator_right('roles', false, 'index');

        $roles_list = Role::moderatorLimit($answer_roles)
        ->companiesLimit($answer_roles)
        ->filials($answer_roles) // $filials должна существовать только для зависимых от филиала, иначе $filials должна null
        ->authors($answer_roles)
        ->systemItem($answer_roles) // Фильтр по системным записям 
        ->template($answer_roles) // Выводим шаблоны в список
        ->pluck('role_name', 'id');

        // dd($departments_list);

        // Инфо о странице
        $page_info = pageInfo($this->entity_name);

        // dd($departments_list);
        
        return view('users.edit', compact('user', 'role', 'role_users', 'roles_list', 'departments_list', 'filials_list', 'page_info'));
    }

    public function destroy(Request $request, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_name, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $user = User::moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $user);

        // Удаляем пользователя с обновлением
        $user = User::moderatorLimit($answer)->where('id', $id)->delete();

        if($user) {return Redirect('/users');} else {abort(403,'Что-то пошло не так!');};
    }


    // --------------------------------------------------------------------------------------------------------------------------------------------------------------
    // СПЕЦИФИЧЕСКИЕ МЕТОДЫ СУЩНОСТИ
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function getauthcompany($company_id)
    {

        // Только для бога
        $this->authorize('god', User::class);

        $auth_user = User::findOrFail(Auth::user()->id);
        $auth_user->company_id = $company_id;
        $auth_user->save();

        return redirect('/getaccess/companies.index');
    }


    public function getauthuser($user_id)
    {

        // Только для бога
        $this->authorize('god', User::class);
        session(['god' => Auth::user()->id]);
        Auth::loginUsingId($user_id);
        return redirect('/getaccess');
    }

    public function getgod()
    {
            // Только для бога
            $this->authorize('god', User::class);

            $user = User::findOrFail(Auth::user()->id);
            $user->company_id = null;
            $user->save();

            return redirect('/getaccess');
    }

    public function returngod(Request $request)
    {
        
        if ($request->session()->has('god')) {

            $god_id = $request->session()->get('god');
            $request->session()->forget('god');
            Auth::loginUsingId($god_id);
        }

        return redirect('/getaccess');
    }

}
