<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Фильтры
use App\Scopes\Traits\CompaniesLimitTraitScopes;
use App\Scopes\Traits\AuthorsTraitScopes;
use App\Scopes\Traits\SystemItemTraitScopes;
use App\Scopes\Traits\FilialsTraitScopes;
use App\Scopes\Traits\TemplateTraitScopes;
use App\Scopes\Traits\ModeratorLimitTraitScopes;

class Location extends Model
{
	use SoftDeletes;
  // Подключаем Scopes для главного запроса
	use CompaniesLimitTraitScopes;
	use AuthorsTraitScopes;
	use SystemItemTraitScopes;
	use FilialsTraitScopes;
	use TemplateTraitScopes;
	use ModeratorLimitTraitScopes;
  /**
   * Атрибуты, которые должны быть преобразованы в даты.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];

  protected $fillable = [
  	'city_id ', 
  	'adress', 
  	'width', 
  	'longitude', 
  ];

  /**
  * Получаем город.
  */
  public function city()
  {
    return $this->belongsTo('App\City');
  }

  /**
  * Получаем компании.
  */
  public function companies()
  {
    return $this->hasMany('App\Company');
  }
}