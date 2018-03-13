﻿@extends('layouts.app')
 
@section('inhead')
  <meta name="description" content="{{ $page_info->page_description }}" />
  {{-- Скрипты меню в шапке --}}
  @include('includes.scripts.menu-inhead')
@endsection

@section('title', $page_info->page_name)

@section('breadcrumbs', Breadcrumbs::render('index', $page_info))

@section('title-content')
<div data-sticky-container id="head-content">
  <div class="sticky sticky-topbar" id="head-sticky" data-sticky data-margin-top="2.4" data-options="stickyOn: small;" data-top-anchor="head-content:top">
    <div class="top-bar head-content">
      <div class="top-bar-left">
        <h2 class="header-content">{{ $page_info->page_name }}</h2>
        @can('create', App\Department::class)
        <a class="icon-add sprite" data-open="filial-add"></a>
        @endcan
      </div>
      <div class="top-bar-right">
        <a class="icon-filter sprite"></a>
        <input class="search-field" type="search" name="search-field" placeholder="Поиск" />
        <button type="button" class="icon-search sprite button"></button>
      </div>
    </div>
    {{-- Блок фильтров --}}
    <div class="grid-x">
      <div class="small-12 cell filters" id="filters">
        <fieldset class="fieldset-filters inputs">
          {{ Form::open(['data-abide', 'novalidate', 'name'=>'filter', 'method'=>'GET']) }}
          <legend>Фильтрация</legend>
          <div class="grid-x grid-padding-x"> 
            <div class="small-6 cell">
              <label>Статус пользователя
                {{ Form::select('user_type', [ 'all' => 'Все пользователи','1' => 'Сотрудник', '2' => 'Клиент'], 'all') }}
              </label>
            </div>
            <div class="small-6 cell">
              <label>Блокировка доступа
                {{ Form::select('access_block', [ 'all' => 'Все пользователи', '1' => 'Доступ блокирован', '' => 'Доступ открыт'], 'all') }}
              </label>
            </div>

            <div class="small-12 medium-12 align-center cell tabs-button">
              {{ Form::submit('Фильтрация', ['class'=>'button']) }}
            </div>
          </div>
        {{ Form::close() }}
        </fieldset>
      </div>
    </div>
  </div>
</div>
@endsection
 
@section('content')
{{-- Список --}}
<div class="grid-x">
  <div class="small-12 cell">

    @php
      $drop = 1;
    @endphp
    {{-- @can('drop', App\Sector::class)
      $drop = 1;
    @endcan --}}

    @if($departments_tree)
      <ul class="vertical menu accordion-menu content-list" id="content-list" data-accordion-menu data-allow-all-closed data-multi-open="false" data-slide-speed="250">
        @foreach ($departments_tree as $department)
         
          @if($department['filial_status'] == 1)
            {{-- Если филиал --}}
            <li class="first-item item @if (isset($department['children']) && isset($department['staff'])) parent @endif" id="departments-{{ $department['id'] }}" data-name="{{ $department['department_name'] }}">
              <ul class="icon-list">
                <li>
                  @can('create', App\Department::class)
                  <div class="icon-list-add sprite" data-open="department-add"></div>
                  @endcan
                </li>
                <li>
                  @if($department['edit'] == 1)
                  <div class="icon-list-edit sprite" data-open="filial-edit"></div>
                  @endif
                </li>
                <li>
                  @if ((count($department['staff']) == 0) && !isset($department['children']) && ($department['system_item'] != 1) && $department['delete'] == 1)
                    <div class="icon-list-delete sprite" data-open="item-delete"></div>
                  @endif
                </li>
              </ul>
              <a data-list="" class="first-link">
                <div class="list-title">
                  <div class="icon-open sprite"></div>
                  <span class="first-item-name">{{ $department['department_name'] }}</span>
                  <span class="number">{{ $department['count'] }}</span>
                </div>
              </a>
              <div class="drop-list checkbox">
                @if ($drop == 1)
                <div class="sprite icon-drop"></div>
                @endif
                <input type="checkbox" name="" id="check-{{ $department['id'] }}">
                <label class="label-check white" for="check-{{ $department['id'] }}"></label> 
              </div>
            @if (isset($department['staff']) || isset($department['children']))
              <ul class="menu vertical medium-list accordion-menu" data-accordion-menu data-allow-all-closed data-multi-open="false">
                @if (isset($department['staff']))
                  @foreach($department['staff'] as $staffer)
                    <li class="medium-item item" id="staff-{{ $staffer['id'] }}" data-name="{{ $staffer['position']['position_name'] }}">
                      <ul class="icon-list">
                          <li>
                            @if(($staffer['system_item'] != 1) && ($staffer['delete'] == 1) && !isset($staffer['user']))
                              <div class="icon-list-delete sprite" data-open="item-delete"></div>
                            @endif
                          </li>
                        </ul>
                      <div class="medium-as-last">{{ $staffer['position']['position_name'] }} ( <a href="/staff/{{ $staffer['id'] }}/edit" class="link-recursion">
                        @if (isset($staffer['user_id']))
                          {{ $staffer['user']['first_name'] }} {{ $staffer['user']['second_name'] }}
                        @else
                          Вакансия
                        @endif
                        </a> )
                      </div>
                      <div class="drop-list checkbox">
                        @if ($drop == 1)
                        <div class="sprite icon-drop"></div>
                        @endif
                        <input type="checkbox" name="" id="check-{{ $staffer['id'] }}">
                        <label class="label-check" for="check-{{ $staffer['id'] }}"></label> 
                      </div>
                    </li>
                  @endforeach
                @endif
                @if (isset($department['children']))
                  @foreach($department['children'] as $department)
                    @include('departments.departments-list', $department)
                  @endforeach
                @endif
              </ul>
            @endif
          </li>
          @endif
        @endforeach
      </ul>
    @endif
  </div>
</div>
@endsection

@section('modals')
{{-- Модалка добавления филиала --}}
<div class="reveal" id="filial-add" data-reveal>
  <div class="grid-x">
    <div class="small-12 cell modal-title">
      <h5>ДОБАВЛЕНИЕ филиала</h5>
    </div>
  </div>
  {{ Form::open(['url'=>'/departments', 'id' => 'form-filial-add', 'data-abide', 'novalidate']) }}
    <div class="grid-x grid-padding-x modal-content inputs">
      <div class="small-10 small-offset-1 cell">
        <label class="input-icon">Введите город
          @include('includes.inputs.city_name', ['value'=>null, 'name'=>'city_name', 'required'=>'required'])
          @include('includes.inputs.city_id', ['value'=>null, 'name'=>'city_id'])
        </label>
        <label>Название филиала
          @include('includes.inputs.name', ['value'=>null, 'name'=>'filial_name'])
        </label>
        <label>Адресс филиала
           @include('includes.inputs.address', ['value'=>null, 'name'=>'filial_address'])
        </label>
        <label>Телефон филиала
          @include('includes.inputs.phone', ['value'=>null, 'name'=>'filial_phone', 'required'=>''])
        </label>
        <input type="hidden" name="filial_db" class="filial-db" value="0" pattern="[0-9]{1}">
      </div>
    </div>
    <div class="grid-x align-center">
      <div class="small-6 medium-4 cell">
        {{ Form::submit('Сохранить', ['class'=>'button modal-button', 'id'=>'submit-filial-add']) }}
      </div>
    </div>
  {{ Form::close() }}
  <div data-close class="icon-close-modal sprite close-modal add-item"></div> 
</div>
{{-- Конец модалки добавления филиала --}}

{{-- Модалка редактирования филиала --}}
<div class="reveal" id="filial-edit" data-reveal>
  <div class="grid-x">
    <div class="small-12 cell modal-title">
      <h5>Редактирование филиала</h5>
    </div>
  </div>
  {{ Form::open([ 'data-abide', 'novalidate', 'id'=>'form-filial-edit']) }}
  {{ method_field('PATCH') }}
    <div class="grid-x grid-padding-x modal-content inputs">
      <div class="small-10 small-offset-1 cell">
        <label class="input-icon">Введите город
          @include('includes.inputs.city_name', ['value'=>null, 'name'=>'city_name', 'required'=>'required'])
          @include('includes.inputs.city_id', ['value'=>null, 'name'=>'city_id'])
        </label>
        <label>Название филиала
          @include('includes.inputs.name', ['value'=>null, 'name'=>'filial_name'])
        </label>
        <label>Адресс филиала
           @include('includes.inputs.address', ['value'=>null, 'name'=>'filial_address'])
        </label>
        <label>Телефон филиала
          @include('includes.inputs.phone', ['value'=>null, 'name'=>'filial_phone', 'required'=>'required'])
        </label>
        <input type="hidden" name="filial_db" class="filial-db" value="1">
      </div>
    </div>
    <div class="grid-x align-center">
      <div class="small-6 medium-4 cell">
        {{ Form::submit('Сохранить', ['class'=>'button modal-button', 'id'=>'submit-filial-edit']) }}
      </div>
    </div>
  {{ Form::close() }}
  <div data-close class="icon-close-modal sprite close-modal add-item"></div> 
</div>
{{-- Конец модалки редактирования филиала --}}

{{-- Модалка добавления отдела --}}
<div class="reveal" id="department-add" data-reveal>
  <div class="grid-x">
    <div class="small-12 cell modal-title">
      <h5>ДОБАВЛЕНИЕ отдела / должности</h5>
    </div>
  </div>
  <div class="grid-x tabs-wrap tabs-margin-top">
    <div class="small-8 small-offset-2 cell">
      <ul class="tabs-list" data-tabs id="tabs">
        <li class="tabs-title is-active"><a href="#add-department" aria-selected="true">Добавить отдел</a></li>
        <li class="tabs-title"><a data-tabs-target="add-position" href="#add-position">Добавить должность</a></li>
      </ul>
    </div>
  </div>
  <div class="tabs-wrap inputs">
    <div class="tabs-content" data-tabs-content="tabs">
      <!-- Добавляем отдел -->
      <div class="tabs-panel is-active" id="add-department">
        {{ Form::open(['url' => '/departments', 'id' => 'form-department-add']) }}
          <div class="grid-x grid-padding-x modal-content inputs">
            <div class="small-10 small-offset-1 cell">
              <label>Добавляем отдел в:
                <select class="departments-list" name="department_parent_id">
            
                </select>
                <input type="hidden" name="filial_id" class="filial-id-field">
              </label>

               <label>Название отдела
                @include('includes.inputs.name', ['value'=>null, 'name'=>'department_name'])
                <div class="item-error">Данный отдел уже существует в этом филиале!</div>
              </label>
              <label class="input-icon">Введите город
                @include('includes.inputs.city_name', ['value'=>null, 'name'=>'city_name', 'required'=>''])
                @include('includes.inputs.city_id', ['value'=>null, 'name'=>'city_id'])
              </label>
              <label>Адресс отдела
                 @include('includes.inputs.address', ['value'=>null, 'name'=>'department_address'])
              </label>
              <label>Телефон отдела
                @include('includes.inputs.phone', ['value'=>null, 'name'=>'filial_phone', 'required'=>''])
              </label>
              <input type="hidden" name="department_db" class="department-db" value="0">
            </div>
          </div>
          <div class="grid-x align-center">
            <div class="small-6 medium-4 cell">
              {{ Form::submit('Сохранить', ['data-close', 'class'=>'button modal-button', 'id'=>'submit-department-add']) }}
            </div>
          </div>
        {{ Form::close() }}
      </div>
      <!-- Добавляем должность -->
      <div class="tabs-panel" id="add-position">
        {{ Form::open(['url' => '/staff', 'id' => 'form-positions-add']) }}
          <div class="grid-x grid-padding-x modal-content inputs">
            <div class="small-10 small-offset-1 cell">
              <label>Добавляем должность в:
                <select class="departments-list" name="department_id">
            
                </select>
                <input type="hidden" name="filial_id" class="filial-id-field">
              </label>
              <label>Должность
                <select class="positions-list" name="position_id">
            
                </select>
              </label>
            </div>
          </div>
          <div class="grid-x align-center">
            <div class="small-6 medium-4 cell">
              {{ Form::submit('Сохранить', ['data-close', 'class'=>'button modal-button', 'id'=>'submit-department-add']) }}
            </div>
          </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
  <div data-close class="icon-close-modal sprite close-modal add-item"></div> 
</div>
{{-- Конец модалки добавления отдела --}}

{{-- Модалка редактирования отдела --}}
<div class="reveal" id="department-edit" data-reveal>
  <div class="grid-x">
    <div class="small-12 cell modal-title">
      <h5>Редактирование отдела</h5>
    </div>
  </div>
  <!-- Редактируем отдел -->
  {{ Form::open(['id' => 'form-department-edit', 'class' => 'form-check-city']) }}
  {{ method_field('PATCH') }}
    <div class="grid-x grid-padding-x modal-content inputs">
      <div class="small-10 small-offset-1 cell">
        <label>Отдел находится в:
          <select class="departments-list" name="department_parent_id">
            
          </select>
          <input type="hidden" name="filial_id" class="filial-id-field">
        </label>
        <label>Название отдела
          @include('includes.inputs.name', ['value'=>null, 'name'=>'department_name'])
          <div class="item-error">Данный отдел уже существует в этом филиале!</div>
        </label>
        <label class="input-icon">Введите город
          @include('includes.inputs.city_name', ['value'=>null, 'name'=>'city_name', 'required'=>''])
          @include('includes.inputs.city_id', ['value'=>null, 'name'=>'city_id'])
        </label>
        <label>Адресс отдела
           @include('includes.inputs.address', ['value'=>null, 'name'=>'department_address'])
        </label>
        <label>Телефон отдела
          @include('includes.inputs.phone', ['value'=>null, 'name'=>'filial_phone', 'required'=>''])
        </label>
        <input type="hidden" name="department_db" class="department-db" value="0">
      </div>
    </div>
    <div class="grid-x align-center">
      <div class="small-6 medium-4 cell">
        {{ Form::submit('Сохранить', ['data-close', 'class'=>'button modal-button', 'id'=>'submit-department-edit']) }}
      </div>
    </div>
  {{ Form::close() }}
  <div data-close class="icon-close-modal sprite close-modal add-item"></div> 
</div>
{{-- Конец модалки отдела --}}

{{-- Модалка удаления с refresh --}}
@include('includes.modals.modal-delete')

{{-- Модалка удаления ajax --}}
@include('includes.modals.modal-delete-ajax')
@endsection

@section('scripts')
{{-- Маска ввода --}}
@include('includes.scripts.inputs-mask')
{{-- Скрипт чекбоксов и перетаскивания для меню --}}
@include('includes.scripts.menu-scripts')
{{-- Скрипт подсветки многоуровневого меню --}}
@include('includes.scripts.multilevel-menu-active-scripts')
{{-- Скрипт модалки удаления ajax --}}
@include('includes.scripts.modal-delete-ajax-script')
{{-- Скрипт модалки удаления ajax --}}
@include('includes.scripts.modal-delete-script')
<script type="text/javascript">
$(function() {
  // Функция появления окна с ошибкой
  // function showError (msg) {
  //   var error = "<div class=\"callout item-error\" data-closable><p>" + msg + "</p><button class=\"close-button error-close\" aria-label=\"Dismiss alert\" type=\"button\" data-close><span aria-hidden=\"true\">&times;</span></button></div>";
  //   return error;
  // };
  // При добавлении филиала ищем город в нашей базе
  function checkCity(city) {
    // Смотрим сколько символов
    var lenCity = city.length;
    // Если символов больше 3 - делаем запрос
    if (lenCity > 2) {
      $('.find-status').removeClass('icon-find-ok');
      $('.find-status').removeClass('sprite-16');
      // Сам ajax запрос
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/cities_list",
        type: "POST",
        data: {city_name: city},
        beforeSend: function () {
          $('.find-status').addClass('icon-load');
        },
        success: function(date){
          $('.find-status').removeClass('icon-load');
          // Удаляем все значения чтобы вписать новые
          $('.table-over').remove();
          var result = $.parseJSON(date);
          var data = '';
          if (result.error_status == 0) {
            // Перебираем циклом
            data = "<table class=\"table-content-search table-over\"><tbody>";
            for (var i = 0; i < result.count; i++) {
              data = data + "<tr data-tr=\"" + i + "\"><td><a class=\"city-add\" data-city-id=\"" + result.cities.city_id[i] + "\">" + result.cities.city_name[i] + "</a></td><td><a class=\"city-add\">" + result.cities.area_name[i] + "</a></td><td><a class=\"city-add\">" + result.cities.region_name[i] + "</a></td></tr>";
            };
            data = data + "</tbody><table>";
          };
          if (result.error_status == 1) {
            $('.find-status').addClass('icon-find-no');
            $('.find-status').addClass('sprite-16');
            data = "<table class=\"table-content-search table-over\"><tbody><tr><td>Населенный пункт не найден в базе данных, @can('create', App\City::class)<a href=\"/cities\" target=\"_blank\">добавьте его!</a>@endcan @cannot('create', App\City::class)обратитесь к администратору!@endcannot</td></tr></tbody><table>";
          };
          // Выводим пришедшие данные на страницу
          $('.input-icon').after(data);
        }
      });
    };
    if (lenCity <= 2) {
      // Удаляем все значения, если символов меньше 3х
      $('.table-over').remove();
      $('.item-error').remove();
      $('.find-status').removeClass('icon-find-ok');
      $('.find-status').removeClass('icon-find-no');
      $('.find-status').removeClass('sprite-16');
      $('.city-id-field').val('');
      // $('#city-name-field').val('');
    };
  };
  // При добавлении филиала ищем город в нашей базе
  $('#form-filial-add .city-check-field').keyup(function() {
    // Получаем фрагмент текста
    var city = $('#form-filial-add .city-check-field').val();
    checkCity(city);
  });
  $('#form-filial-edit .city-check-field').keyup(function() {
    // Получаем фрагмент текста
    var city = $('#form-filial-edit .city-check-field').val();
    checkCity(city);
  });
  $('#form-department-add .city-check-field').keyup(function() {
    // Получаем фрагмент текста
    var city = $('#form-department-add .city-check-field').val();
    checkCity(city);
  });
  $('#form-department-edit .city-check-field').keyup(function() {
    // Получаем фрагмент текста
    var city = $('#form-department-edit .city-check-field').val();
    checkCity(city);
  });
  // Редактируем филиал
  $(document).on('click', '[data-open="filial-edit"]', function() {
    // Блокируем кнопку
    $('.submit-filial-edit').prop('disabled', false);
    // Получаем данные о филиале
    var id = $(this).closest('.parent').attr('id').split('-')[1];
    $('#form-filial-edit').attr('action', '/departments/' + id);
    // Сам ajax запрос
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/departments/" + id + "/edit",
      type: "GET",
      success: function(date){
        var result = $.parseJSON(date);
        $('#form-filial-edit .city-check-field').val(result.city_name);
        $('#form-filial-edit .city-id-field').val(result.city_id);
        $('#form-filial-edit .name-field').val(result.filial_name);
        $('#form-filial-edit .address-field').val(result.filial_address);
        $('#form-filial-edit .phone-field').val(result.filial_phone);
        $('#form-filial-edit .filial-db-edit').val(1);
      }
    });
  });
  // Добавление отдела или должности
  $(document).on('click', '[data-open="department-add"]', function() {
    var parent = $(this).closest('.parent').attr('id').split('-')[1];
    var filial = $(this).closest('.first-item').attr('id').split('-')[1];
    $('.filial-id-field').val(filial);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/departments_list",
      type: "POST",
      data: {filial_id: filial},
      success: function(date){
        var result = $.parseJSON(date);
        var data = '';
        var selected = '';
        $.each(result, function(index, value) {
          selected = '';
          if (index == parent) {
            selected = 'selected';
          };
          data = data + "<option value=\"" + index + "\" " + selected + ">" + value + "</option>";
        });
        $('.departments-list').append(data);
      }
    });
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/positions_list",
      type: "POST",
      data: {filial_id: filial},
      success: function(date){
        var result = $.parseJSON(date);
        var data = '';
        $.each(result, function(index, value) {
          data = data + "<option value=\"" + index + "\">" + value + "</option>";
        });
        $('.positions-list').append(data);
      }
    });
  });
  // Редактируем отдел
  $(document).on('click', '[data-open="department-edit"]', function() {
    var parent = $(this).closest('.parent').attr('id').split('-')[1];
    var filial = $(this).closest('.first-item').attr('id').split('-')[1];
    $('.filial-id-field').val(filial);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/departments_list",
      type: "POST",
      data: {filial_id: filial},
      success: function(date){
        var result = $.parseJSON(date);
        var data = '';
        var selected = '';
        $.each(result, function(index, value) {
          data = data + "<option value=\"" + index + "\">" + value + "</option>";
        });
        $('.departments-list').append(data);
      }
    });
    var id = $(this).closest('.parent').attr('id').split('-')[1];
    // Отмечам в какой пункт будем добавлять
    // $('#departments-list>[value="' + id + '"]').prop('selected', true);
    // Блокируем кнопку
    $('#submit-department-edit').prop('disabled', false);
    // Получаем данные о филиале
    $('#form-department-edit').attr('action', '/departments/' + id);
    // Сам ajax запрос
    // alert(id);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/departments/" + id + "/edit",
      type: "GET",
      success: function(date){
        var result = $.parseJSON(date);
        // alert(result);
        $('#form-department-edit .city-name-field').val(result.city_name);
        $('#form-department-edit .city-id-field').val(result.city_id);
        $('#form-department-edit .name-field').val(result.department_name);
        $('#form-department-edit .address-field').val(result.filial_address);
        $('#form-department-edit .phone-field').val(result.filial_phone);
        $('.department-db').val(1);
        $('#form-department-edit .filial-id-field').val(result.filial_id);
        $('.departments-list>[value="' + result.department_parent_id + '"]').prop('selected', true);
      }
    });
  });
  // При клике на город в модальном окне добавления филиала заполняем инпуты
  $(document).on('click', '.city-add', function() {
    var cityId = $(this).closest('tr').find('a.city-add').data('city-id');
    var cityName = $(this).closest('tr').find('[data-city-id=' + cityId +']').html();
    $('.city-id-field').val(cityId);
    $('.city-check-field').val(cityName);
    $('.table-over').remove();
    $('.find-status').addClass('icon-find-ok');
    $('.find-status').addClass('sprite-16');
    $('.find-status').removeClass('icon-find-no');
  });
  function departmentCheck (department, submit) {
    // Блокируем кнопку
    $(submit).prop('disabled', true);
    $('.department-db').val(0);
    // Первая буква отдела заглавная
    department = department.charAt(0).toUpperCase() + department.substr(1);
    // Смотрим сколько символов
    var lenDepartment = department.length;
    // Если символов больше 3 - делаем запрос
    if (lenDepartment > 2) {
      // Сам ajax запрос
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/department_check",
        type: "POST",
        data: {department_name: department, filial_id: $('.filial-id-field').val(), department_db: $('.department-db').val()},
        beforeSend: function () {
          $('.find-department').addClass('icon-load');
        },
        success: function(date){
          $('.find-department').removeClass('icon-load');
          // Удаляем все значения чтобы вписать новые
          var result = $.parseJSON(date);
          // alert(date);
          if (result.error_status == 0) {
            // Выводим пришедшие данные на страницу
            $('.item-error').css('display', 'block');
          };
          if (result.error_status == 1) {
            $('.item-error').css('display', 'none');
            $('.department-db').val(1);
            $(submit).prop('disabled', false);
          };
        }
      });
    };
    if (lenDepartment <= 2) {
      // Удаляем все значения, если символов меньше 3х
      $('.item-error').css('display', 'none');
      $('.item-error').remove();
      // $('#city-name-field').val('');
    };
  };
  // Чекаем отдел в нашей бд
  $('#form-department-add .department-name-field').keyup(function() {
    var submit = '#submit-department-add';
    // Получаем фрагмент текста
    var department = $('#form-department-add .name-field').val();
    departmentCheck (department, submit);
  });
  $('#form-department-edit .department-name-field').keyup(function() {
    var submit = '#submit-department-edit';
    // Получаем фрагмент текста
    var department = $('#form-department-edit .name-field').val();
    departmentCheck (department, submit);
  });

  // При закрытии модалки очищаем поля
  $(document).on('click', '.close-modal', function() {
    $('.city-check-field').val('');
    $('.city-id-field').val('');
    $('.name-field').val('');
    $('.address-field').val('');
    $('.phone-field').val('');
    $('.departments-list').empty();
    $('.positions-list').empty();
    $('.table-over').remove();
    $('.item-error').css('display', 'none');
    $('.find-status').removeClass('icon-find-ok');
    $('.find-status').removeClass('icon-find-no');
    $('.find-status').removeClass('sprite-16');
  });
  // Открываем меню и подменю, если только что добавили населенный пункт
  @if(!empty($data))
    // Общие правила
    // Подсвечиваем Филиал
    $('#departments-{{ $data['section_id'] }}').addClass('first-active').find('.icon-list:first').attr('aria-hidden', 'false').css('display', 'block');
    // Отображаем отдел и филиал, без должностей
    if ({{ $data['item_id'] }} == 0) {
      var firstItem = $('#departments-{{ $data['section_id'] }}').find('.medium-list:first');
      // Открываем аккордион
      $('#content-list').foundation('down', firstItem); 
    } else {
      // Перебираем родителей и подсвечиваем их
      $.each($('#departments-{{ $data['item_id'] }}').parents('.parent-item').get().reverse(), function (index) {
        $(this).children('.medium-link:first').addClass('medium-active');
        $(this).children('.icon-list:first').attr('aria-hidden', 'false').css('display', 'block');
        $('#content-list').foundation('down', $(this).closest('.medium-list'));
      });
      // Если родитель содержит не пустой элемент
      if ($('#departments-{{ $data['item_id'] }}').parent('.parent').has('.parent-item')) {
        $('#content-list').foundation('down', $('#departments-{{ $data['item_id'] }}').closest('.medium-list'));
      };
      // Если элемент содержит вложенность, открываем его
      if ($('#departments-{{ $data['item_id'] }}').hasClass('parent')) {
        $('#departments-{{ $data['item_id'] }}').children('.medium-link:first').addClass('medium-active');
        $('#departments-{{ $data['item_id'] }}').children('.icon-list:first').attr('aria-hidden', 'false').css('display', 'block');
        $('#content-list').foundation('down', $('#departments-{{ $data['item_id'] }}').children('.medium-list:first'));
      }
    };
  @endif
});
</script>
@endsection