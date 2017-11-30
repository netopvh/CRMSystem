<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    public function setBirthdayAttribute($value) {
        $date_parts = explode('.', $value);
        $this->attributes['birthday'] = $date_parts[0].'-'.$date_parts[1].'-'.$date_parts[2];
    }

    public function setPassportDateAttribute($value) {
        $date_parts = explode('.', $value);
        $this->attributes['birthday'] = $date_parts[0].'-'.$date_parts[1].'-'.$date_parts[2];
    }

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 
        'email', 
        'password', 
        'nickname', 

        'first_name', 
        'second_name', 
        'patronymic', 
        'sex', 
        'birthday', 

        'phone', 
        'extra_phone', 
        'telegram_id', 
        'city_id', 
        'address', 

        'orgform_status', 
        'company_name', 
        'inn', 
        'kpp', 
        'account_settlement', 
        'account_correspondent', 
        'bank', 

        'passport_number', 
        'passport_released', 
        'passport_date', 
        'passport_address', 

        'contragent_status', 
        'lead_id', 
        'employee_id', 
        'access_block', 
    ];


    public function setAddressAttribute($value){
        $d = $value . " г. Иркутск";
        return $value;
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}