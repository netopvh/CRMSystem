<?php

namespace App\Scopes\Traits;

trait ModeratorLimitTraitScopes
{

    // Фильтрация записей модерируемых записей по филиалу и автору
    public function scopeModeratorLimit($query, $answer)
    {

        $entity_dependence = $answer['dependence'];
        $moderator = $answer['moderator'];

        // Получаем данные из сессии
        $session  = session('access');
        $user_id = $session['user_info']['user_id'];
        // dd($entity_dependence);

        if(($entity_dependence == false)||($entity_dependence == null)){

            if($moderator == true){

                return $query
                ->where(function ($query) use ($user_id) {$query->whereNull('moderated')->orWhere('moderated', 1);});

            } else {

                return $query
                ->where(function ($query) use ($user_id) {$query->whereNull('moderated');})->orWhere(function ($query) use ($user_id) {$query->Where('moderated', 1)->Where('author_id', $user_id);});
            };


        } else {

            if($moderator == true){

                $moderator_filials = collect(getLS('users', 'moderator', 'filials'))->keys()->toarray();
                return $query
                ->where(function ($query) use ($moderator_filials) {$query->whereNull('moderated')->orwhere('moderated', 1)->WhereIn('filial_id', $moderator_filials);})
                ->Orwhere(function ($query) use ($user_id) {$query->Where('moderated', 1)->Where('author_id', $user_id);});

            } else {

                $moderator_filials = collect(getLS('users', 'moderator', 'filials'))->keys()->toarray();
                return $query
                ->where(function ($query) use ($moderator_filials) {$query->whereNull('moderated')->WhereIn('filial_id', $moderator_filials);})
                ->Orwhere(function ($query) use ($user_id) {$query->Where('moderated', 1)->Where('author_id', $user_id);});

            };


        };
    }
}