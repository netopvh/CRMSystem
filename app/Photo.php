<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

// Заготовки
use App\Scopes\Traits\CompaniesLimitTraitScopes;
use App\Scopes\Traits\AuthorsTraitScopes;
use App\Scopes\Traits\SystemItemTraitScopes;
use App\Scopes\Traits\FilialsTraitScopes;
use App\Scopes\Traits\TemplateTraitScopes;
use App\Scopes\Traits\ModeratorLimitTraitScopes;

// Фильтры
use App\Scopes\Filters\CityFilter;
use App\Scopes\Filters\BooklistFilter;

class Photo extends Model
{
    use Notifiable;

    // Подключаем Scopes для главного запроса
    use CompaniesLimitTraitScopes;
    use AuthorsTraitScopes;
    use SystemItemTraitScopes;
    use FilialsTraitScopes;
    use TemplateTraitScopes;
    use ModeratorLimitTraitScopes;
    use BooklistFilter;

    protected $fillable = [

    ];

    // Получаем компанию
    public function company()
    {
    return $this->belongsTo('App\Company');
    }

    public function cur_news()
    {
    return $this->hasOne('App\News');
    }

    // Получаем альбом
	public function album()
	{
	   return $this->belongsToMany('App\Album', 'album_entity', 'entity_id', 'album_id')->where('entity', 'photo');
	}

	// Получаем автора
	public function author()
	{
	return $this->belongsTo('App\User', 'author_id');
	}

    public function user()
  {
    return $this->hasOne('App\User');
  }
}