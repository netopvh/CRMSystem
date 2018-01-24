<?php

namespace App\Policies;

use App\Policies\Traits\PoliticTrait;
use App\User;
use App\RightsRole;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;
// use App\Http\Controllers\Session;

class UserPolicy
{
    
    use HandlesAuthorization;
    use PoliticTrait;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    
    // Проверяем на бога. Имеет приоритет над всеми остльными методами
    // Если true - предоставляем доступ
    // Если null - отправляем на проверку в последующих методах
    // если false - блокируем доступ
    
    public function before($user)
    {
        if (Auth::user()->god == 1) {$result = true;} else {$result = null;};
        return $result;
    }

    public function index(User $user)
    {
        $session  = session('access');
        if(isset($session['all_rights']['index-users-allow']))
        {$result = true;} else {$result = false;};
        return $result;
    }

    public function view(User $user, User $user_item)
    {

        $session  = session('access');
        $nolimit_status = isset($session['all_rights']['nolimit-users-allow']);
        $main_update_status = isset($session['all_rights']['update-users-allow']['departments'][$user_item->filial_id]);
        $update_status = isset($session['all_rights']['update-users-allow']);

        if(($update_status)&&($main_update_status || $main_update_status)){$result = true;} else {$result = false;};
        return $result;
    }

    public function create(User $user)
    {
        $session  = session('access');
        if(isset($session['all_rights']['create-users-allow']) && (!isset($session['all_rights']['create-users-deny'])))
        {$result = true;} else {$result = false;};
        return $result;
    }

    public function update(User $user, User $user_item)
    { 
        $result = $this->getstatus('users', $user_item);
        return $result;
    }

    public function delete(User $user, User $model)
    {
        $session  = session('access');
        if(isset($session['all_rights']['delete-users-allow']) && (!isset($session['all_rights']['delete-users-deny'])))
        {$result = true;} else {$result = false;};
        return $result;
    }
}
