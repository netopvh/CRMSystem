<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Фильтры
use App\Scopes\Traits\CompaniesLimitTraitScopes;
use App\Scopes\Traits\AuthorsTraitScopes;
use App\Scopes\Traits\SystemItemTraitScopes;
use App\Scopes\Traits\FilialsTraitScopes;
use App\Scopes\Traits\TemplateTraitScopes;
use App\Scopes\Traits\ModeratorLimitTraitScopes;

class ActionEntity extends Model
{

  // Подключаем Scopes для главного запроса
  use CompaniesLimitTraitScopes;
  use AuthorsTraitScopes;
  use SystemItemTraitScopes;
  use FilialsTraitScopes;
  use TemplateTraitScopes;  
  use ModeratorLimitTraitScopes;

  protected $table = 'action_entity';


    /**
  * Получаем полиморфную запись (ID права).
  */
  public function right()
  {
    return $this->hasOne('App\Right', 'action_entity', 'id', 'object_entity');
  }


    /**
  * Получаем полиморфную запись (ID права).
  */
  public function entity()
  {
    return $this->belongsTo('App\Entity');
  }


    /**
  * Получаем полиморфную запись (ID права).
  */
  public function action()
  {
    return $this->belongsTo('App\Action');
  }

}
