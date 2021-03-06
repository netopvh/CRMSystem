<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

use App\PhotoSetting;

use Illuminate\Support\Facades\Blade;


use Illuminate\Support\Facades\Auth;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Если существует таблица с меню
        if (Schema::hasTable('photo_settings')) {
            config()->set('photo_settings', PhotoSetting::whereNull('company_id')
                ->first()
                ->toArray());
        } else {
            // Умолчания на случай, если нет доступа к базе (Для формирования autoload)
            $settings = [];
            config()->set('photo_settings', [
                $settings['img_small_width'] = 0,
                $settings['img_small_height'] = 0,
                $settings['img_medium_width'] = 0,
                $settings['img_medium_height'] = 0,
                $settings['img_large_width'] = 0,
                $settings['img_large_height'] = 0,

                $settings['img_formats'] = 0,
                $settings['strict_mode'] = 0,

                $settings['img_min_width'] = 0,
                $settings['img_min_height'] = 0,
                $settings['img_max_size'] = 0,
            ]);

        }

        // Проверки If Else на шаблонах

        // Display
        Blade::if('display', function ($item) {
            return $item->display == 1;
        });

        // Moderation
        Blade::if('moderation', function ($item) {
            return $item->moderation == 1;
        });

    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      //
    }
}
