@extends('layouts.app')

@section('inhead')
<meta name="description" content="{{ $page_info->page_description }}" />
{{-- Скрипты меню в шапке --}}
@include('includes.scripts.sortable-inhead')
@endsection

@section('title', $page_info->name)

@section('breadcrumbs', Breadcrumbs::render('section', $parent_page_info, $site, $page_info))

@section('title-content')
{{-- Меню --}}
@include('includes.title-content', ['page_info' => $page_info, 'class' => App\Catalog::class, 'type' => 'menu'])
@endsection

@section('content')
{{-- Список --}}
<div class="grid-x">
    <div class="small-12 cell">
        <ul class="vertical menu accordion-menu content-list" id="content" data-accordion-menu data-multi-open="false" data-slide-speed="250" data-entity-alias="catalogs">
            @if($catalogs)

            {{-- Шаблон вывода и динамического обновления --}}
            @include('catalogs.enter', ['grouped_items' => $catalogs, 'class' => 'App\Catalog', 'entity' => $entity, 'type' => 'edit'])

            @endif
        </ul>
    </div>
</div>
@endsection

@section('modals')
<section id="modal"></section>
{{-- Модалка удаления ajax --}}
@include('includes.modals.modal-delete-ajax')
@endsection

@section('scripts')

{{-- Маска ввода --}}
@include('includes.scripts.inputs-mask')

{{-- Скрипт подсветки многоуровневого меню --}}
@include('includes.scripts.multilevel-menu-active-scripts')

{{-- Скрипт отображения на сайте --}}
@include('includes.scripts.ajax-display')

{{-- Скрипт системной записи --}}
@include('includes.scripts.ajax-system')

<script type="text/javascript">

    // Берем алиас сайта
    var siteAlias = '{{ $alias }}';

    $(function() {

        // ------------------------------ Удаление ajax -------------------------------------------
        $(document).on('click', '[data-open="item-delete-ajax"]', function() {
            // Находим описание сущности, id и название удаляемого элемента в родителе
            var parent = $(this).closest('.item');
            var entity_alias = parent.attr('id').split('-')[0];
            var id = parent.attr('id').split('-')[1];
            var name = parent.data('name');
            $('.title-delete').text(name);
            $('.delete-button-ajax').attr('id', 'del-' + entity_alias + '-' + id);
        });

        // Подтверждение удаления и само удаление
        $(document).on('click', '.delete-button-ajax', function(event) {

            // Блочим отправку формы
            event.preventDefault();
            var entity_alias = $(this).attr('id').split('-')[1];
            var id = $(this).attr('id').split('-')[2];

            // Ajax
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/sites/' + siteAlias + '/catalogs/' + id,
                type: "DELETE",
                success: function (html) {
                    $('#content').html(html);
                    Foundation.reInit($('#content'));
                    $('#delete-button-ajax').removeAttr('id');
                    $('.title-delete').text('');
                }
            });
        });

        // Функция появления окна с ошибкой
        function showError (msg) {
            var error = "<div class=\"callout item-error\" data-closable><p>" + msg + "</p><button class=\"close-button error-close\" aria-label=\"Dismiss alert\" type=\"button\" data-close><span aria-hidden=\"true\">&times;</span></button></div>";
            return error;
        };

        // Обозначаем таймер для проверки
        var timerId;
        var time = 400;

        // Первая буква заглавная
        function newParagraph (name) {
            name = name.charAt(0).toUpperCase() + name.substr(1).toLowerCase();
            return name;
        };

        // ------------------- Проверка на совпадение имени --------------------------------------
        function catalogCheck (name, submit, db) {

            // Блокируем аттрибут базы данных
            $(db).val(0);

            // Смотрим сколько символов
            var lenname = name.length;

            // Если символов больше 3 - делаем запрос
            if (lenname > 3) {

                // Первая буква сектора заглавная
                name = newParagraph (name);

                // Сам ajax запрос
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/sites/' + siteAlias + '/catalog_check',
                    type: "POST",
                    data: {name: name},
                    beforeSend: function () {
                        $('.find-status').addClass('icon-load');
                    },
                    success: function(date){
                        $('.find-status').removeClass('icon-load');
                        var result = $.parseJSON(date);
                        // Если ошибка
                        if (result.error_status == 1) {
                            $(submit).prop('disabled', true);
                            $('.item-error').css('display', 'block');
                            $(db).val(0);
                        } else {
                            // Выводим пришедшие данные на страницу
                            $(submit).prop('disabled', false);
                            $('.item-error').css('display', 'none');
                            $(db).val(1);
                        };
                    }
                });
            };
            // Удаляем все значения, если символов меньше 3х
            if (lenname <= 3) {
                $(submit).prop('disabled', false);
                $('.item-error').css('display', 'none');
                $(db).val(0);
            };
        };

        // ---------------------------- Категория -----------------------------------------------

        // ----------- Добавление -------------
        // Открываем модалку
        $(document).on('click', '[data-open="first-add"]', function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/sites/' + siteAlias + '/catalogs/create',
                type: "GET",
                success: function(html){
                    // alert(html);
                    $('#modal').html(html);
                    $('#first-add').foundation();
                    $('#first-add').foundation('open');
                }
            }); 
        });

        // Проверка существования
        $(document).on('keyup', '#form-first-add .name-field', function() {
            // Получаем фрагмент текста
            var name = $('#form-first-add .name-field').val();
            // Указываем название кнопки
            var submit = '.submit-add';
            // Значение поля с разрешением
            var db = '#form-first-add .first-item';
            // Выполняем запрос
            clearTimeout(timerId);   
            timerId = setTimeout(function() {
                catalogCheck (name, submit, db)
            }, time); 
        });

        // ------------------------------- Вложенные категории --------------------------------------------

        // ----------- Добавление -------------
        // Модалка
        $(document).on('click', '[data-open="medium-add"]', function() {

            var parent = $(this).closest('.item').attr('id').split('-')[1];
            var category = $(this).closest('.first-item').attr('id').split('-')[1];

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/sites/' + siteAlias + '/catalogs/create',
                type: "GET",
                data: {category_id: category, parent_id: parent},
                success: function(html){
                    $('#modal').html(html);
                    $('#medium-add').foundation();
                    $('#medium-add').foundation('open');
                    $('#category-id').val(category);
                }
            }); 
        });

        // ------------------------ Кнопка добавления ---------------------------------------
        $(document).on('click', '.submit-add', function(event) {
            event.preventDefault();

            // alert($(this).closest('form').serialize());
            // Ajax запрос
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/sites/' + siteAlias + '/catalogs',
                type: "POST",
                data: $(this).closest('form').serialize(),
                success:function(html) {
                    // alert(html);
                    $('#content').html(html);
                    Foundation.reInit($('#content'));
                }
            });
        });


        // ---------------------------------- Закрытие модалки -----------------------------------
        $(document).on('click', '.icon-close-modal, .submit-edit, .submit-add, .submit-services-product-add', function() {
            $(this).closest('.reveal-overlay').remove();
        });
    });
</script>
@endsection