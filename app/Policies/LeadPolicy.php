<?php

namespace App\Policies;

use App\Policies\Traits\PoliticTrait;
use App\User;
use App\Lead;
// use App\Supplier;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{

    use HandlesAuthorization;
    use PoliticTrait;

    protected $entity_name = 'leads';
    protected $entity_dependence = false;

    public function before($user)
    {
        // if (Auth::user()->god == 1) {$result = true;} else {$result = null;};
        // return $result;
    }

    public function index(User $user)
    {
        $result = $this->getstatus($this->entity_name, null, 'index', $this->entity_dependence);
        return $result;
    }

    public function view(User $user, Lead $model)
    {
        $result = $this->getstatus($this->entity_name, $model, 'view', $this->entity_dependence);
        return $result;
    }

    public function create(User $user)
    {
        $result = $this->getstatus($this->entity_name, null, 'create', $this->entity_dependence);
        return $result;
    }

    public function update(User $user, Lead $model)
    { 
        if($model->manager_id == 1){
            $result = false;
        } else {
            $result = $this->getstatus($this->entity_name, $model, 'update', $this->entity_dependence);
        };
        return $result;
    }

    // public function edit(User $user, Lead $model)
    // { 
    //     $result = $this->getstatus($this->entity_name, $model, 'delete', $this->entity_dependence);
    //     return $result;
    // }

    public function delete(User $user, Lead $model)
    {
        $result = $this->getstatus($this->entity_name, $model, 'delete', $this->entity_dependence);

        if (($model->challenges_count > 0) || ($model->claims_count > 0)) {
            return false;
        } else {
            return $result;
        }
    }

    public function moderator(User $user, Lead $model)
    {
        $result = $this->getstatus($this->entity_name, $model, 'moderator', $this->entity_dependence);
        return $result;
    }

    public function automoderate(User $user, Lead $model)
    {
        $result = $this->getstatus($this->entity_name, $model, 'automoderate', $this->entity_dependence);
        return $result;
    }

    public function display(User $user)
    {
        $result = $this->getstatus($this->entity_name, null, 'display', $this->entity_dependence);
        // dd($result);
        return $result;
    }

    public function system(User $user, Lead $model)
    {
        $result = $this->getstatus($this->entity_name, $model, 'system', $this->entity_dependence);
        return $result;
    }
    
    public function god(User $user)
    {
        if(Auth::user()->god){return true;} else {return false;};
    }

}
