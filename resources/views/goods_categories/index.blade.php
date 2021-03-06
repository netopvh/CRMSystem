@extends('layouts.app')

@section('inhead')
<meta name="description" content="{{ $page_info->page_description }}" />
{{-- Скрипты меню в шапке --}}
@include('includes.scripts.sortable-inhead')
@endsection

@section('title', $page_info->name)

@section('breadcrumbs', Breadcrumbs::render('index', $page_info))

@section('title-content')
{{-- Меню --}}
@include('includes.title-content', ['page_info' => $page_info, 'class' => App\GoodsCategory::class, 'type' => 'menu'])
@endsection

@section('content')
{{-- Список --}}
<div class="grid-x">
    <div class="small-12 cell">
        <ul class="vertical menu accordion-menu content-list" id="content" data-accordion-menu data-multi-open="false" data-slide-speed="250" data-entity-alias="goods_categories">
            @if($goods_categories)

            {{-- Шаблон вывода и динамического обновления --}}
            @include('includes.menu-views.enter', ['grouped_items' => $goods_categories, 'class' => 'App\GoodsCategory', 'entity' => $entity, 'type' => 'edit'])

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
{{-- Скрипт модалки удаления ajax --}}
@include('includes.scripts.delete-ajax-script')

{{-- Маска ввода --}}
@include('includes.scripts.inputs-mask')

{{-- Скрипт подсветки многоуровневого меню --}}
@include('includes.scripts.multilevel-menu-active-scripts')

{{-- Скрипт отображения на сайте --}}
@include('includes.scripts.ajax-display')

{{-- Скрипт системной записи --}}
@include('includes.scripts.ajax-system')

<script type="text/javascript">

  $(function() {
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
  function goodsCategoryCheck (name, submit, db) {

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
      url: "/admin/goods_category_check",
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
  $(document).on('click', '[data-open="modal-create"]', function() {
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: '/admin/goods_categories/create',
    type: "GET",
    success: function(html){
        // alert(html);
        $('#modal').html(html);
        $('#modal-create').foundation();
        $('#modal-create').foundation('open');
    }
});
});

  // Проверка существования
  $(document).on('keyup', '#form-modal-create .name-field', function() {
    // Получаем фрагмент текста
    var name = $('#form-modal-create .name-field').val();
    // Указываем название кнопки
    var submit = '.submit-add';
    // Значение поля с разрешением
    var db = '#form-modal-create .first-item';
    // Выполняем запрос
    clearTimeout(timerId);
    timerId = setTimeout(function() {
      goodsCategoryCheck (name, submit, db)
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
    url: '/admin/goods_categories/create',
    type: "GET",
    data: {category_id: category, parent_id: parent},
    success: function(html){
        $('#modal').html(html);
        $('#medium-add').foundation();
        $('#medium-add').foundation('open');
        $('.category-id').val(category);
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
    url: '/admin/goods_categories',
    type: "POST",
    data: $(this).closest('form').serialize(),
    success:function(html) {
        // alert(html);
        $('#content').html(html);
        Foundation.reInit($('#content'));
    }
});
});

  $(document).on('click', '.submit-goods-product-add', function(event) {
    event.preventDefault();

    // alert($(this).closest('form').serialize());
    // Ajax запрос
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: '/admin/goods_products',
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
  $(document).on('click', '.icon-close-modal, .submit-edit, .submit-add, .submit-goods-product-add', function() {
    $(this).closest('.reveal-overlay').remove();
});
});
</script>
@endsection