<?php

namespace App\Policies;

use App\Policies\Traits\PoliticTrait;
use App\User;

use App\Photo;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PhotoPolicy
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

    protected $entity_name = 'photos';
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

    public function view(User $user, Photo $model)
    {
        $result = $this->getstatus($this->entity_name, $model, 'view', $this->entity_dependence);
        return $result;
    }

    public function create(User $user)
    {
        $result = $this->getstatus($this->entity_name, null, 'create', $this->entity_dependence);
        return $result;
    }

    public function update(User $user, Photo $model)
    { 

        $result = $this->getstatus($this->entity_name, $model, 'update', $this->entity_dependence);
        return $result;
    }

    public function delete(User $user, Photo $model)
    {
        $result = $this->getstatus($this->entity_name, $model, 'delete', $this->entity_dependence);
        return $result;
    }

    public function moderator(User $user, Photo $model)
    {
        $result = $this->getstatus($this->entity_name, $model, 'moderator', $this->entity_dependence);
        return $result;
    }

    public function automoderate(User $user, Photo $model)
    {
        $result = $this->getstatus($this->entity_name, $model, 'automoderate', $this->entity_dependence);
        return $result;
    }

    public function publisher(User $user)
    {
        $result = $this->getstatus($this->entity_name, null, 'publisher', $this->entity_dependence);
        return $result;
    }

    public function god(User $user)
    {
        // dd('lol');
        if(Auth::user()->god){return true;} else {return false;};
    }
}
