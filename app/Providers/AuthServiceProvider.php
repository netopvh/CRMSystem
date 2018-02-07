<?php

namespace App\Providers;

use App\User;
use App\RightsRole;
use App\Company;
use App\Role;

use App\Right;
use App\Entity;
use App\Region;
use App\Area;
use App\City;
use App\Department;
use App\Employee;
use App\Menu;
use App\Navigation;
use App\Page;
use App\Position;
use App\Site;
use App\Staffer;

use App\Policies\UserPolicy;
use App\Policies\RightsRolePolicy;
use App\Policies\CompanyPolicy;
use App\Policies\RolePolicy;

use App\Policies\RightPolicy;
use App\Policies\EntityPolicy;

use App\Policies\RegionPolicy;
use App\Policies\AreaPolicy;
use App\Policies\CityPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\MenuPolicy;
use App\Policies\NavigationPolicy;
use App\Policies\PagePolicy;
use App\Policies\PositionPolicy;
use App\Policies\SitePolicy;
use App\Policies\StafferPolicy;

use Illuminate\Support\Facades\Gate as GateContract;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy', 
        // Page::class => PagePolicy::class,
        User::class => UserPolicy::class, 
        RightsRole::class => RightsRolePolicy::class, 
        Company::class => CompanyPolicy::class, 
        Right::class => RightPolicy::class, 
        Entity::class => EntityPolicy::class, 
        Role::class => RolePolicy::class,
        Position::class => PositionPolicy::class,
        Region::class => RegionPolicy::class,
        Area::class => AreaPolicy::class,
        City::class => CityPolicy::class,
        Department::class => DepartmentPolicy::class,
        Employee::class => EmployeePolicy::class,
        Menu::class => MenuPolicy::class,
        Navigation::class => NavigationPolicy::class,
        Page::class => PagePolicy::class,
        Site::class => SitePolicy::class,
        Staffer::class => StafferPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */

    // public function boot()
    // {

    //     $this->registerPolicies();

    //     Gate::define('index-user', function ($user, $access) {
    //     return  $result = $access->where(['right_action' => 'view-user'])->count() == 1;

    //     });

    // }

    public function boot()
    {
      $this->registerPolicies();
    }

}
