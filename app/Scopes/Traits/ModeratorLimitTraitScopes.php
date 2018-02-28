<?php

namespace App\Scopes\Traits;

trait ModeratorLimitTraitScopes
{

    // Фильтрация записей модерируемых записей по филиалу и автору
    public function scopeModeratorLimit($query, $answer)
    {

        $entity_dependence = $answer['dependence'];
        $moderator = $answer['moderator']['result'];
        $moderator_filials = $answer['moderator']['filials'];

        // Получаем данные из сессии
        $session  = session('access');
        $user_id = $session['user_info']['user_id'];
        $user_status = $session['user_info']['user_status'];
        // dd($entity_dependence);

        // Если бог, то не отсеиваем неотмодерированные записи
        if($user_status == 1){return $query;};


        if(($entity_dependence == false)||($entity_dependence == null)){

            if($moderator == true){

                return $query
                ->where(function ($query) use ($user_id) {$query->whereNull('moderation')->orWhere('moderation', 1);});

            } else {

                return $query
                ->where(function ($query) use ($user_id) {$query->whereNull('moderation');})->orWhere(function ($query) use ($user_id) {$query->Where('moderation', 1)->Where('author_id', $user_id);});
            };


        } else {

            if($moderator == true){
                // dd($moderator_filials);

                return $query
                ->WhereNull('moderation')
                ->Orwhere(function ($query) use ($moderator_filials) {$query->Where('moderation', 1)->WhereIn('filial_id', $moderator_filials);})
                ->Orwhere(function ($query) use ($user_id) {$query->Where('moderation', 1)->Where('author_id', $user_id);});

            } else {

                return $query
                ->where(function ($query) use ($moderator_filials) {$query->whereNull('moderation')->WhereIn('filial_id', $moderator_filials);})
                ->Orwhere(function ($query) use ($user_id) {$query->Where('moderation', 1)->Where('author_id', $user_id);});

            };


        };
    }
}
