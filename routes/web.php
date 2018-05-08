<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return view('layouts.enter');
});

Route::get('/img/{path}', 'ImageController@show')->where('path', '.*');

Route::get('/lol', function () {
  return view('demo');
});

Auth::routes();

Route::any('/getaccess', 'GetAccessController@set')->middleware('auth')->name('getaccess.set');

// Директории
Route::get('directories', 'DirectoryController@index')->middleware('auth')->name('directories.index');

Route::resource('/users', 'UserController')->middleware('auth');


// Контроллер категорий альбомов
Route::resource('/albums_categories', 'AlbumsCategoryController')->middleware('auth');
// Текущая добавленная/удаленная категория альбомов
Route::any('/get_albums_categories', 'AlbumsCategoryController@get_content')->middleware('auth');
// Проверка на существование категории альбомов
Route::post('/albums_category_check', 'AlbumsCategoryController@albums_category_check')->middleware('auth');
// Select категорий альбомов
Route::post('/albums_categories_list', 'AlbumsCategoryController@albums_categories_list')->middleware('auth');
// Сортировка категорий альбомов
Route::post('/albums_categories_sort', 'AlbumsCategoryController@albums_categories_sort')->middleware('auth');

// Альбомы
Route::resource('/albums', 'AlbumController')->middleware('auth');
Route::get('/albums', 'AlbumController@index')->middleware('auth')->name('albums.index');
Route::get('/albums/create', 'AlbumController@create')->middleware('auth')->name('albums.create');
Route::get('/albums/{alias}', 'AlbumController@show')->middleware('auth')->name('albums.show');
Route::post('/albums', 'AlbumController@store')->middleware('auth')->name('albums.store');
Route::get('/albums/{alias}/edit', 'AlbumController@edit')->middleware('auth')->name('albums.edit');
Route::patch('/albums/{id}', 'AlbumController@update')->middleware('auth')->name('albums.update');
Route::delete('/albums/{id}', 'AlbumController@destroy')->middleware('auth')->name('albums.destroy');

// Route::get('/albums/{alias}', 'AlbumController@sections')->middleware('auth')->name('albums.photos');
// Группа с префиксом
Route::prefix('/albums/{alias}')->group(function () {
  // Фотографии
  Route::resource('/photos', 'PhotoController')->middleware('auth');



  // Загрузка фоток через ajax через dropzone.js
});

// Сортировка альбомов
Route::post('/photos_sort', 'PhotoController@photos_sort')->middleware('auth');

// Получение альбомов по категории
Route::post('/albums_list', 'AlbumController@albums_list')->middleware('auth');

// Получение альбома
Route::post('/get_album', 'AlbumController@get_album')->middleware('auth');

// Сортировка альбомов
Route::post('/albums_sort', 'AlbumController@albums_sort')->middleware('auth');


// Продукция
Route::resource('products', 'ProductController')->middleware('auth');
// Сортировка альбомов
Route::post('/products_sort', 'ProductController@products_sort')->middleware('auth');


// Контроллер категорий продукции
Route::resource('/products_categories', 'ProductsCategoryController')->middleware('auth');
// Текущая добавленная/удаленная категория альбомов
Route::any('/get_products_categories', 'ProductsCategoryController@get_content')->middleware('auth');
// Проверка на существование категории альбомов
Route::post('/products_category_check', 'ProductsCategoryController@products_category_check')->middleware('auth');
// Select категорий альбомов
Route::post('/products_categories_list', 'ProductsCategoryController@products_categories_list')->middleware('auth');
// Сортировка категорий альбомов
Route::post('/products_categories_sort', 'ProductsCategoryController@products_categories_sort')->middleware('auth');

// Компании
Route::resource('/companies', 'CompanyController')->middleware('auth');
// Проверка существования компании в базе по ИНН
Route::post('/companies/check_company', 'CompanyController@checkcompany')->middleware('auth')->name('companies.checkcompany');

// Маршруты для правил доступа
Route::resource('/rights', 'RightController')->middleware('auth');

// Маршруты для групп доступа
Route::resource('/roles', 'RoleController')->middleware('auth');
// Route::resource('rightrole', 'RightroleController')->middleware('auth');

Route::get('/roles/{id}/setting', 'RoleController@setting')->middleware('auth')->name('roles.setting');
Route::post('/roles/setright', 'RoleController@setright')->middleware('auth')->name('roles.setright');

// Получение роли дял пользоователя
Route::any('/get_role', 'RoleController@get_role')->middleware('auth');

// Маршрут связи юзера с ролями и отделами
Route::resource('/roleuser', 'RoleUserController')->middleware('auth');

// Маршруты для сущностей
Route::resource('/entities', 'EntityController')->middleware('auth');

// Маршруты для папок (директорий)
Route::resource('/folders', 'FolderController')->middleware('auth');

// Авторизуемся под выбранной компанией
Route::get('/getauthcompany/{company_id}', 'UserController@getauthcompany')->middleware('auth')->name('users.getauthcompany');

// Авторизуемся под выбранным пользователем
Route::get('/getauthuser/{user_id}', 'UserController@getauthuser')->middleware('auth')->name('users.getauthuser');

// Сбрасываем для бога company_id
Route::get('/getgod', 'UserController@getgod')->middleware('auth')->name('users.getgod');

// Получаем доступ бога
Route::get('/returngod', 'UserController@returngod')->middleware('auth')->name('users.returngod');

// Контроллеры для отображения областей
Route::resource('/regions', 'RegionController')->middleware('auth');
// Сортировка областей
Route::post('/regions_sort', 'RegionController@regions_sort')->middleware('auth');
// Контроллеры для отображения районов    населенных пунктов
Route::resource('/areas', 'AreaController')->middleware('auth');
// Сортировка областей
Route::post('/areas_sort', 'AreaController@areas_sort')->middleware('auth');
// Контроллеры для отображения населенных пунктов    
Route::resource('/cities', 'CityController')->middleware('auth');
// Текущий добавленный/удаленный город
Route::any('/get_cities', 'CityController@get_content')->middleware('auth');
// Проверка на существование города
Route::post('/city_check', 'CityController@city_check')->middleware('auth');
// Сортировка населенных пунктов
Route::post('/cities_sort', 'CityController@cities_sort')->middleware('auth');
// Таблица городов
Route::post('/cities_list', 'CityController@cities_list')->middleware('auth');
// Получаем области и города из vk
Route::post('/city_vk', 'CityController@get_vk_city')->middleware('auth');
Route::post('/region', 'RegionController@get_vk_region')->middleware('auth');

// Тестовый маршрут проверки пришедших с вк данных
// Route::get('/city_vk/{city}', 'CityController@get_vk_city')->middleware('auth');

// Контроллеры для отображения филиалов, отделов и должностей
Route::resource('/departments', 'DepartmentController')->middleware('auth');
// Текущий добавленный/удаленный сектор
Route::any('/get_departments', 'DepartmentController@get_content')->middleware('auth');
// Текущий добавленный/удаленный отдел
Route::get('/current_department/{section_id}/{item_id}', 'DepartmentController@current_department')->middleware('auth');
// Проверка на существование филиала/отдела
Route::post('/department_check', 'DepartmentController@department_check')->middleware('auth');
// Список отделов филиала
Route::post('/departments_list', 'DepartmentController@departments_list')->middleware('auth');
// Сортировка отделов
Route::post('/departments_sort', 'DepartmentController@departments_sort')->middleware('auth');

// Должности
Route::resource('/positions', 'PositionController')->middleware('auth');
// Список отделов филиала и доступных должностей
Route::post('/positions_list', 'PositionController@positions_list')->middleware('auth');

// Контроллер штата компании
Route::resource('/staff', 'StafferController')->middleware('auth');

// Контроллер сотрудников
Route::resource('/employees', 'EmployeeController')->middleware('auth');

// Контроллер секторов
Route::resource('/sectors', 'SectorController')->middleware('auth');
// Текущий добавленный/удаленный сектор
Route::any('/get_sectors', 'SectorController@get_content')->middleware('auth');
// Проверка на существование сектора
Route::post('/sector_check', 'SectorController@sector_check')->middleware('auth');
// Select секторов
Route::post('/sectors_list', 'SectorController@sectors_list')->middleware('auth');
// Сортировка секторов
Route::post('/sectors_sort', 'SectorController@sectors_sort')->middleware('auth');

// Контроллер списков
Route::resource('/booklists', 'BooklistController')->middleware('auth');

Route::post('/setbooklist', 'BooklistController@setbooklist')->middleware('auth')->name('booklists.setbooklist');
Route::get('/updatebooklist', 'BooklistController@setbooklist')->middleware('auth')->name('booklists.updatebooklist');


// Контроллер отображения сайтов 
Route::get('/sites', 'SiteController@index')->middleware('auth')->name('sites.index');
Route::get('/sites/create', 'SiteController@create')->middleware('auth')->name('sites.create');
Route::post('/sites', 'SiteController@store')->middleware('auth')->name('sites.store');
Route::get('/sites/{alias}/edit', 'SiteController@edit')->middleware('auth')->name('sites.edit');
Route::patch('/sites/{id}', 'SiteController@update')->middleware('auth')->name('sites.update');
Route::delete('/sites/{id}', 'SiteController@destroy')->middleware('auth')->name('sites.destroy');
Route::get('/sites/{alias}', 'SiteController@sections')->middleware('auth')->name('sites.sections');
// Группа с префиксом
Route::prefix('/sites/{alias}')->group(function () {
	// Странички
  Route::resource('/pages', 'PageController')->middleware('auth');
  // Навигация и меню
  Route::resource('/navigations', 'NavigationController')->middleware('auth');
  // Текущая добавленная/удаленная навигация
  Route::any('/get_navigations', 'NavigationController@get_content')->middleware('auth');
	// Проверка на существование навигации
  Route::post('/navigation_check', 'NavigationController@navigation_check')->middleware('auth');

  Route::resource('/menus', 'MenuController')->middleware('auth');

  // Новости
  Route::resource('/news', 'NewsController')->middleware('auth');
});

// Сортировка отделов
Route::post('/news_sort', 'NewsController@news_sort')->middleware('auth');

// Route::any('/modal', 'MenuController@modal')->middleware('auth');
// Сортировка навигаций
Route::post('/navigations_sort', 'NavigationController@navigations_sort')->middleware('auth');
// Сортировка меню
Route::post('/menus_sort', 'MenuController@menus_sort')->middleware('auth');

// Route::resource('/menusite', 'MenuSiteController')->middleware('auth');
// Отображение сессии
Route::get('/show_session', 'HelpController@show_session')->middleware('auth')->name('help.show_session');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
