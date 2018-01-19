<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use App\Page;
use App\Right;
use App\RoleUser;
use App\Department;
use App\Http\Controllers\Session;
use App\Scopes\ModerationScope;

// Модели которые отвечают за работу с правами + политики
use App\Role;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


// Запросы и их валидация
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUser;

// Прочие необходимые классы
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Подключение политики
        // $this->authorize('index', User::class);
        
        $user = Auth::user();

        // Получаем сессию
        $session  = session('access');

        if(!isset($session)){abort(403, 'Нет сессии!');};

        // Получаем список авторов
        $list_authors = $session['list_authors'];

        // Показываем богу всех авторов
        if($user->god == 1)
        {

            $filials = null;
            $system_item = 1;
            $authors = null;

        } else {

            // ОСНОВНЫЕ ПРОВЕРКИ --------------------------------------------------------------------------------------------------------------------

                // Указываем - являеться ли сущность зависимой от филиала
                // false - независима / true - зависима
                $dependence = true;


                // Управление зависимостью через право
                // Если выбрано "Нет ограичений" мы снимаем филиальную зависимость

                if(isset($session['all_rights']['nolimit-users-allow']) && (!isset($session['all_rights']['nolimit-users-deny'])))
                {
                    $dependence = false;
                } else {
                    $dependence = true;
                };
        };


        // ПРОВЕРЯЕМ ПРАВО НА ПРОСМОТР НЕ ОТМОДЕРИРОВАННЫХ ЗАПИСЕЙ  -----------------------------------------------------------------------------------
        // Проверяем право просмотра системных записей:
        
        if(isset($session['all_rights']['moderator-users-allow']) && (!isset($session['all_rights']['moderator-users-deny'])))
        {
            $moderator = ModerationScope::class;
        } else {
            $moderator = null;
        };


        // ---------------------------------------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // ---------------------------------------------------------------------------------------------------------------------------------------------

        $dependence = true;

        if(isset($user->company_id))
        {
            // Если у пользователя есть компания
            $users = User::withoutGlobalScope($moderator)
            ->whereCompany_id($user->company_id)
            ->filials($dependence, $session) // $filials должна существовать только для зависимых от филиала, иначе $filials должна равняться null
            ->whereGod(null)
            ->authors($dependence, $session)
            ->systemItem($session) // Фильтр по системным записям
            ->orderBy('moderated', 'desc')
            ->paginate(30);

        } else {

            // Если нет, то бог без компании
            if($user->god == 1)
            {
                $users = User::withoutGlobalScope(ModerationScope::class)
                ->orderBy('moderated', 'desc')
                ->paginate(30);
            };
        };

	    return view('users.index', compact('users', 'access', 'session'));
	}

    //
    public function create()
    {
        // $this->authorize('create', User::class);

        // Получаем сессию
        $session  = session('access');
        if(!isset($session)){abort(403, 'Нет сессии!');};

        // Получаем список ID филиалов в которых у нас есть право на текущую операцию
        $filials = [];
        foreach($session['filial_rights'] as $key => $filial){
                if(isset($filial['create-users-allow']) && (!isset($filial['create-users-deny']))){
                $filials[] = $filial['filial'];
            }
        }

        $list_filials = Department::whereIn('id', $filials)->pluck('department_name', 'id');

    	$user = new User;
        $roles = new Role;
    	return view('users.create', compact('user', 'roles', 'list_filials'));
    }


    public function store(UpdateUser $request)
    {

        // $this->authorize('create', User::class);

        // Получаем сессию
        $session  = session('access');
        if(!isset($session)){abort(403, 'Нет сессии!');};

        // Получаем список ID филиалов в которых у нас есть право на текущую операцию
        $filials = [];
        foreach($session['filial_rights'] as $key => $filial){
                if(isset($filial['create-users-allow']) && (!isset($filial['create-users-deny']))){
                $filials[] = $key;
            }
        }


        $auth_user = Auth::user();
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
        $user->author_id = $auth_user->id;

        if(isset($access['automoderate-users-allow']) && (!isset($access['automoderate-users-deny'])))
        {
            $moderator = ModerationScope::class;
        } else {
            $moderator = null;
        };





        // Если у пользователя есть назначенная компания и пользователь не являеться богом
        if(isset($auth_user->company_id)&&($auth_user->god != 1)){
            $user->company_id = $auth_user->company_id;
            $user->filial_id = $session['user_info']['filial_id'];

        // Если бог авторизован под компанией
        } elseif(isset($auth_user->company_id)&&($auth_user->god == 1)) {
            $user->company_id = $auth_user->company_id;

        } elseif(($auth_user->company_id == null) && ($auth_user->god == 1)){
            $user->system_item = 1;
        } else {
            abort(403);
        };

        $user->save();


        // // Создаем компанию под пользователя
        // // Если стоит отметка о том, что нужно создать компанию.
        // if($user->orgform_status == '1'){

        //     //Проверим по ИНН есть ли компания в базе
        //     $company_inn = Company::where('company_inn', $user->user_inn)->count();
        //     if($company_inn == 1){
        //         // Компания существует
                
        //     } else {
        //         // Компания не существует

        //     $company = new Company;
        //     $company->company_name = $request->company_name;
        //     $company->kpp = $request->kpp;
        //     $company->account_settlement = $request->account_settlement;
        //     $company->account_correspondent = $request->account_correspondent;
        //     $company->bank = $request->bank;
        //     $company->user_id = $user_id;

        //     $company->save();
        //     };

        // } else{

        // // Когда отметки нет
         
        // };

        return redirect('users');
    }




    public function update(UpdateUser $request, $id)
    {

        $user = User::findOrFail($id);
        // $this->authorize('update', $user);

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

    	if(($request->extra_phone != NULL)&&($request->extra_phone != "")){
    		$user->extra_phone = cleanPhone($request->extra_phone);
    	} else {$user->extra_phone = NULL;};

    	$user->telegram_id = $request->telegram_id;
    	$user->city_id = $request->city_id;
    	$user->address = $request->address;

    	$user->orgform_status = $request->orgform_status;

    	$user->user_inn = $request->inn;

    // $user->company_name = $request->company_name;
    // $user->kpp = $request->kpp;
    // $user->account_settlement = $request->account_settlement;
    // $user->account_correspondent = $request->account_correspondent;
    // $user->bank = $request->bank;

    	$user->passport_address = $request->passport_address;
    	$user->passport_number = $request->passport_number;
    	$user->passport_released = $request->passport_released;
    	$user->passport_date = $request->passport_date;

    	$user->user_type = $request->user_type;
    	$user->lead_id = $request->lead_id;
    	$user->employee_id = $request->employee_id;
    	$user->access_block = $request->access_block;

    	$user->group_action_id = $request->group_action_id;
    	$user->group_locality_id = $request->group_locality_id;

		$user->save();
 
		return redirect('users');
    	// $users = User::all();

    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        // $this->authorize('view', $user);

        $roles = new Role;
    	return view('users.show', compact('user', 'roles'));
    }

    public function edit($id)
    {
        $auth_user = Auth::user();
        $user = User::findOrFail($id);

        // $this->authorize('update', $user);

        if($auth_user->god == null) {


        // Получаем сессию
        $session  = session('access');

        if(!isset($session)){abort(403, 'Нет сессии!');};
        $user_filial_id = $session['user_info']['filial_id'];

        // Получаем список ID филиалов в которых у нас есть право на текущую операцию
        $filials = [];

        foreach($session['filial_rights'] as $key => $filial){
                if(isset($filial['edit-users-allow']) && (!isset($filial['edit-users-deny']))){
                $filials[] = $filial['filial'];
            }
        }

        if($filials == null){abort(403, 'Недостаточно прав!');};

        // $filials[] = $user_filial_id;
        $list_filials = Department::whereIn('id', $filials)->where('filial_status', 1)->orderBy('department_name')->pluck('department_name', 'id');

        };


        $role = new Role;
        $role_users = RoleUser::whereUser_id($id)->get();

        $roles = Role::whereCompany_id($auth_user->company_id)->pluck('role_name', 'id');
        $departments = Department::whereCompany_id($auth_user->company_id)->pluck('department_name', 'id');

        
        Log::info('Позырили страницу Users, в частности смотрели пользователя с ID: '.$id);
        return view('users.edit', compact('user', 'role', 'role_users', 'roles', 'departments', 'list_filials'));
    }


    public function destroy($id)
    {
        // Удаляем пользователя с обновлением
        $user = User::destroy($id);
        if ($user) {
          return Redirect('/users');
        } else {
          echo 'Произошла ошибка';
        }; 
    }


    public function getauthcompany($company_id)
    {

        // $this->authorize('update', $user);

        $auth_user = Auth::user();

        if($auth_user->god == 1){
            $auth_user->company_id = $company_id;
            $auth_user->save();         
        }
        return redirect('companies');
    }


    public function getauthuser(Request $request, $user_id)
    {

        // $this->authorize('update', $user);

        $auth_user = Auth::user();

        if($auth_user->god == 1){
            session(['god' => $auth_user->id]);
            Auth::loginUsingId($user_id);
        };

        return redirect('/getaccess');
    }

    public function getgod()
    {
        // $this->authorize('update', User::class); 
        if(Auth::user()->god == 1){
            $user = User::findOrFail(Auth::user()->id);
            $user->company_id = null;
            $user->save();
        }
        return redirect('companies');
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
