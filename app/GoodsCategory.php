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

class GoodsCategory extends Model
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
        'description',
        'seo_description',
        'photo_id',
        'parent_id',
        'goods_mode_id',
        'category_id',
        'author_id',
        'editor_id',
    ];

    // Вложенные
    public function childs()
    {
        return $this->hasMany('App\GoodsCategory', 'parent_id');
    }

    // Компания
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    // Группы
    public function products()
    {
        return $this->hasMany('App\GoodsProduct');
    }

    // Режим
    public function mode()
    {
        return $this->belongsTo('App\GoodsMode', 'goods_mode_id');
    }

    // Аватар
    public function photo()
    {
        return $this->belongsTo('App\Photo');
    }

    // Метрики
    // Один
    public function one_metrics()
    {
        return $this->morphToMany('App\Metric', 'metric_entity')->where('set_status', 'one');
    }

    // Набор
    public function set_metrics()
    {
        return $this->morphToMany('App\Metric', 'metric_entity')->where('set_status', 'set');
    }

    // Состав (только сырье)
    public function compositions()
    {
        return $this->morphedByMany('App\RawsArticle', 'compositions');
    }

    // Производители
    public function manufacturers()
    {
        return $this->belongsToMany('App\Company', 'goods_category_manufacturer', 'goods_category_id', 'manufacturer_id');
    }

    // Направление
    public function direction()
    {
        return $this->morphOne('App\Direction', 'category')->where('archive', false);
    }

}
