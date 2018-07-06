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

// Фильтры
// use App\Scopes\Filters\Filter;
// use App\Scopes\Filters\BooklistFilter;
// use App\Scopes\Filters\DateIntervalFilter;

class ProductsCategory extends Model
{

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
    'name',
    'parent_id',
    'category_status',
    ];

    // Получаем компании.
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function products_mode()
    {
        return $this->belongsTo('App\ProductsMode');
    }

    public function photo()
    {
        return $this->belongsTo('App\Photo');
    }

    // Получаем метрики
    public function metrics()
    {
        return $this->belongsToMany('App\Metric', 'metric_entity', 'entity_id', 'metric_id')->where('entity', 'products_categories');
    }

    // Получаем состав
    public function compositions()
    {
        return $this->belongsToMany('App\Product', 'compositions', 'products_category_id', 'composition_id');
    }

}
