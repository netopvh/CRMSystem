@extends('layouts.app')

@section('title', 'Новая группа сырья')

@section('breadcrumbs', Breadcrumbs::render('create', $page_info))

@section('title-content')
<div class="top-bar head-content">
    <div class="top-bar-left">
       <h2 class="header-content">ДОБАВЛЕНИЕ НОВОЙ ГРУППЫ СЫРЬЯ</h2>
   </div>
   <div class="top-bar-right">
   </div>
</div>
@endsection

@section('content')

{{ Form::open(['route' => 'raws_products.store', 'data-abide', 'novalidate']) }}
@include('raws_products.form', ['submit_text' => 'Добавить'])
{{ Form::close() }}

@endsection

@section('scripts')
@include('includes.scripts.inputs-mask')

<script type="text/javascript">
	// При смене категории единиц измерения меняем список единиц измерения
    $(document).on('change', '#select-units_categories', function() {
        $.post('/admin/get_units_list', {units_category_id: $(this).val()}, function(html){
            $('#select-units').html(html);
        });
    });
</script>

@endsection



