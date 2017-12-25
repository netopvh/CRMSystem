@extends('layouts.app')
@include('vacancies.inhead')

@section('title', 'Редактировать сотрудника')

@section('title-content')
	<div class="top-bar head-content">
    <div class="top-bar-left">
       <h2 class="header-content">РЕДАКТИРОВАТЬ сотрудника</h2>
    </div>
    <div class="top-bar-right">
    </div>
  </div>
@endsection

@section('content')

  {{ Form::model($vacancy, ['route' => ['vacancies.update', $vacancy->id], 'data-abide', 'novalidate']) }}
  {{ method_field('PATCH') }}

    @include('vacancies.form', ['submitButtonText' => 'Редактировать сотрудника', 'param'=>''])
    
  {{ Form::close() }}

@endsection

@include('vacancies.scripts')


