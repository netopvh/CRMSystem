<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

// Scopes для главного запроса
use App\Scopes\Traits\CompaniesLimitTraitScopes;
use App\Scopes\Traits\AuthorsTraitScopes;
use App\Scopes\Traits\SystemItemTraitScopes;
use App\Scopes\Traits\FilialsTraitScopes;
use App\Scopes\Traits\TemplateTraitScopes;
use App\Scopes\Traits\ModeratorLimitTraitScopes;

// Подключаем кеш
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

// Фильтры
// use App\Scopes\Filters\Filter;
// use App\Scopes\Filters\BooklistFilter;
// use App\Scopes\Filters\DateIntervalFilter;

class Catalog extends Model
{
    // Включаем кеш
    use Cachable;

    use SoftDeletes;

    // Включаем Scopes
    use CompaniesLimitTraitScopes;
    use AuthorsTraitScopes;
    use SystemItemTraitScopes;
    use FilialsTraitScopes;
    use TemplateTraitScopes;
    use ModeratorLimitTraitScopes;

    // Фильтры
    // use Filter;
    // use BooklistFilter;
    // use DateIntervalFilter;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'company_id',
        'name',
        'alias',
        'parent_id',
        'category_id',
    ];


    // Вложенные
    public function childs()
    {
        return $this->hasMany('App\Catalog', 'parent_id');
    }

    // Сайт
    public function site()
    {
        return $this->belongsTo('App\Site');
    }

    // Аавтор
    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }

    // Аватар
    public function photo()
    {
        return $this->belongsTo('App\Photo');
    }

    // Услуги
    public function services()
    {
        return $this->morphedByMany('App\Service', 'catalog_products')->withPivot('id', 'display', 'sort');
    }

    // Товары
    public function goods()
    {
        return $this->morphedByMany('App\Goods', 'catalog_products')->withPivot('id', 'display', 'sort');
    }

     // Сырье
    public function raws()
    {
        return $this->morphedByMany('App\Raw', 'catalog_products')->withPivot('id', 'display', 'sort');
    }


}
