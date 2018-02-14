@extends('layouts.app')

@section('inhead')
  @include('includes.scripts.pickmeup-inhead')
@endsection

@section('title', 'Новый пользователь')

@section('title-content')
	<div class="top-bar head-content">
    <div class="top-bar-left">
       <h2 class="header-content">СОЗДАНИЕ НОВОГО ПОЛЬЗОВАТЕЛЯ</h2>
    </div>
    <div class="top-bar-right">
    </div>
  </div>
@endsection

@section('content')

  {{ Form::open(['route' => 'users.store', 'data-abide', 'novalidate', 'class' => 'form-check-city']) }}
    @include('users.form', ['submitButtonText' => 'Добавить пользователя', 'param' => ''])
  {{ Form::close() }}

@endsection

@section('modals')
  {{-- Модалка удаления с ajax --}}
  @include('includes.modals.modal-delete-ajax')
@endsection

@section('scripts')
  @include('includes.scripts.city-list')
  @include('includes.scripts.inputs-mask')
  @include('includes.scripts.pickmeup-script')
@endsection



