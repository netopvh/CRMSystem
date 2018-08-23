@extends('layouts.app')

@section('title', 'Новая группа товаров')

@section('breadcrumbs', Breadcrumbs::render('create', $page_info))

@section('title-content')
	<div class="top-bar head-content">
    <div class="top-bar-left">
       <h2 class="header-content">ДОБАВЛЕНИЕ НОВОЙ ГРУППЫ ТОВАРОВ</h2>
    </div>
    <div class="top-bar-right">
    </div>
  </div>
@endsection

@section('content')

  {{ Form::open(['url' => '/admin/goods_products', 'data-abide', 'novalidate', 'class' => '']) }}
    @include('goods_products.form', ['submitButtonText' => 'Добавить группу', 'param' => ''])
  {{ Form::close() }}

@endsection

@section('scripts')
  @include('includes.scripts.cities-list')
  @include('includes.scripts.inputs-mask')
  
@endsection


