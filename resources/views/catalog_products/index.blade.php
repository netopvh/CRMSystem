@extends('layouts.app')

@section('inhead')
<meta name="description" content="{{ $page_info->page_description }}" />
{{-- Скрипты таблиц в шапке --}}
@include('includes.scripts.tablesorter-inhead')
@endsection

@section('title', $page_info->name)

@section('breadcrumbs', Breadcrumbs::render('index', $page_info))

@section('exel')
@include('includes.title-exel', ['entity' => $page_info->alias])
@endsection

@section('content-count')
{{-- Количество элементов --}}
  @if(!empty($catalogs))
    {{ num_format($catalogs->total(), 0) }}
  @endif
@endsection

@section('title-content')
{{-- Таблица --}}
@include('includes.title-content', ['page_info' => $page_info, 'class' => App\CatalogProduct::class, 'type' => 'table'])
@endsection

@section('control-content')
<div class="grid-x grid-padding-x">
    <div class="small-12 cell inputs">

        <div class="grid-x grid-margin-x">
            <div class="small-12 medium-6 cell">
                <label>Название каталога
                    @include('includes.inputs.name', ['value'=>null, 'name'=>'name', 'required' => true])
                    <div class="sprite-input-right find-status" id="name-check"></div>
                    <div class="item-error">Такой каталог уже существует!</div>
                </label>
            </div>

            <div class="small-12 medium-6 cell">
                <label>Алиас каталога
                    @include('includes.inputs.varchar', ['name'=>'alias', 'value'=>null])
                    <div class="sprite-input-right find-status" id="alias-check"></div>
                    <div class="item-error">Каталог с таким алиасом уже существует!</div>
                </label>
            </div>

        </div>

    </div>
</div>
@endsection

@section('content')
{{-- Таблица --}}
<div class="grid-x">
    <div class="small-12 cell">
        <table class="content-table tablesorter" id="content" data-sticky-container data-entity-alias="services">
            <thead class="thead-width sticky sticky-topbar" id="thead-sticky" data-sticky data-margin-top="6.2" data-sticky-on="medium" data-top-anchor="head-content:bottom">
                <tr id="thead-content">
                    <th class="td-drop"></th>
                    <th class="td-checkbox checkbox-th"><input type="checkbox" class="table-check-all" name="" id="check-all"><label class="label-check" for="check-all"></label></th>
                    <th class="td-name">Название товара</th>
                    <th class="td-type">Тип товара</th>
                    <th class="td-catalog">Каталог</th>

                    @if(Auth::user()->god == 1)
                    <th class="td-company-id">Компания</th>
                    @endif

                    <th class="td-author">Автор</th>
                    <th class="td-control"></th>
                    <th class="td-archive"></th>
                </tr>
            </thead>
            <tbody data-tbodyId="1" class="tbody-width">
                @if(!empty($catalogs))

                @foreach($catalogs as $catalog)

                @if (count($catalog->services) > 0)
                @foreach ($catalog->services as $service)
                <tr class="item @if($service->moderation == 1)no-moderation @endif" id="services-{{ $service->id }}" data-name="{{ $service->services_article->name }}">
                    <td class="td-drop"><div class="sprite icon-drop"></div></td>
                    <td class="td-checkbox checkbox">
                        <input type="checkbox" class="table-check" name="service_id" id="check-{{ $service->id }}"
                        {{-- Если в Booklist существует массив Default (отмеченные пользователем позиции на странице) --}}
                        @if(!empty($filter['booklist']['booklists']['default']))
                        {{-- Если в Booklist в массиве Default есть id-шник сущности, то отмечаем его как checked --}}
                        @if (in_array($service->id, $filter['booklist']['booklists']['default'])) checked
                        @endif
                        @endif
                        >
                        <label class="label-check" for="check-{{ $service->id }}"></label>
                    </td>

                    <td class="td-name"><a href="/admin/services/{{ $service->id }}/edit">{{ $service->services_article->name }}</a></td>
                    <td class="td-type">Услуга</td>

                    <td class="td-catalog">
                        <a href="/admin/services?catalog_id%5B%5D={{ $catalog->id }}" class="filter_link" title="Фильтровать">{{ $catalog->name }}</a><br>

                    </td>

                    @if(Auth::user()->god == 1)
                    <td class="td-company-id">@if(!empty($service->company->name)) {{ $service->company->name }} @else @if($service->system_item == null) Шаблон @else Системная @endif @endif</td>
                    @endif

                    <td class="td-author">@if(isset($service->author->first_name)) {{ $service->author->first_name . ' ' . $service->author->second_name }} @endif</td>

                    {{-- Элементы управления --}}
                    @include('includes.control.table-td', ['item' => $service])

                    <td class="td-archive">
                        @if ($service->system_item != 1)
                        @can('delete', $service)
                        <a class="icon-delete sprite" data-open="item-archive"></a>
                        @endcan
                        @endif
                    </td>
                </tr>

                @endforeach
                @endif
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="grid-x" id="pagination">
    <div class="small-6 cell pagination-head">
        <span class="pagination-title">Кол-во записей: {{ $catalogs->count() }}</span>
        {{ $catalogs->appends(isset($filter['inputs']) ? $filter['inputs'] : null)->links() }}
    </div>
</div>
@endsection

@section('modals')
<section id="modal"></section>

{{-- Модалка удаления с refresh --}}
@include('includes.modals.modal-archive')

@endsection

@section('scripts')

{{-- Скрипт чекбоксов, сортировки и перетаскивания для таблицы --}}
@include('includes.scripts.tablesorter-script')
@include('includes.scripts.sortable-table-script')

{{-- Скрипт отображения на сайте --}}
@include('includes.scripts.ajax-display')

{{-- Скрипт системной записи --}}
@include('includes.scripts.ajax-system')

{{-- Скрипт чекбоксов --}}
@include('includes.scripts.checkbox-control')

{{-- Скрипт модалки удаления --}}
@include('includes.scripts.modal-archive-script')

@include('includes.scripts.inputs-mask')
@include('catalog_products.scripts')

<script type="text/javascript">


    // Обозначаем таймер для проверки
    // var timerId;
    // var time = 400;

    // // Первая буква заглавная
    // function newParagraph (name) {
    //   name = name.charAt(0).toUpperCase() + name.substr(1).toLowerCase();
    //   return name;
    // };

    // ------------------- Проверка на совпадение имени --------------------------------------
    // function serviceCheck (name, submit, db) {

    //   // Блокируем аттрибут базы данных
    //   $(db).val(0);

    //   // Смотрим сколько символов
    //   var lenname = name.length;

    //   // Если символов больше 3 - делаем запрос
    //   if (lenname > 3) {

    //     // Первая буква сектора заглавная
    //     name = newParagraph (name);

    //     // Сам ajax запрос
    //     $.ajax({
    //       headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //       },
    //       url: "/admin/service_check",
    //       type: "POST",
    //       data: {name: name},
    //       beforeSend: function () {
    //         $('.find-status').addClass('icon-load');
    //       },
    //       success: function(date){
    //         $('.find-status').removeClass('icon-load');
    //         var result = $.parseJSON(date);
    //         // Если ошибка
    //         if (result.error_status == 1) {
    //           $(submit).prop('disabled', true);
    //           $('.item-error').css('display', 'block');
    //           $(db).val(0);
    //         } else {
    //           // Выводим пришедшие данные на страницу
    //           $(submit).prop('disabled', false);
    //           $('.item-error').css('display', 'none');
    //           $(db).val(1);
    //         };
    //       }
    //     });
    //   };
    //   // Удаляем все значения, если символов меньше 3х
    //   if (lenname <= 3) {
    //     $(submit).prop('disabled', false);
    //     $('.item-error').css('display', 'none');
    //     $(db).val(0);
    //   };
    // };

    // ---------------------------- Продукция -----------------------------------------------

    // ----------- Добавление -------------
    // Открываем модалку
    $(document).on('click', '[data-open="modal-create"]', function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/services/create',
            type: "GET",
            success: function(html){
                $('#modal').html(html);
                $('#modal-create').foundation();
                $('#modal-create').foundation('open');
            }
        });
    });

    // Проверка существования
    // $(document).on('keyup', '#form-modal-create .name-field', function() {

    //   // Получаем фрагмент текста
    //   var name = $('#form-modal-create .name-field').val();

    //   // Указываем название кнопки
    //   var submit = '.modal-button';

    //   // Значение поля с разрешением
    //   var db = '#form-modal-create .first-item';

    //   // Выполняем запрос
    //   clearTimeout(timerId);
    //   timerId = setTimeout(function() {
    //     serviceCheck (name, submit, db)
    //   }, time);
    // });

    $(document).on('click', '.close-modal', function() {
      // alert('lol');
      $('.reveal-overlay').remove();
  });
</script>
@endsection
