@extends('layouts.app')
@include('employees.inhead')

@section('title', 'Редактировать сотрудника')

@section('title-content')
	<div class="top-bar head-content">
    <div class="top-bar-left">
       <h2 class="header-content">РЕДАКТИРОВАТЬ уволенного сотрудника</h2>
    </div>
    <div class="top-bar-right">
    </div>
  </div>
@endsection

@section('content')

  {{ Form::model($employee, ['route' => ['employees.update', $employee->id], 'data-abide', 'novalidate']) }}
  {{ method_field('PATCH') }}

    @include('employees.form', ['submitButtonText' => 'Редактировать уволенного сотрудника', 'param'=>''])
    
  {{ Form::close() }}

@endsection

@include('employees.scripts')

