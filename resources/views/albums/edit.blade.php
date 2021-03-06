@extends('layouts.app')

@section('title', 'Редактировать альбом')

@section('breadcrumbs', Breadcrumbs::render('alias-edit', $page_info, $album))

@section('title-content')
<div class="top-bar head-content">
    <div class="top-bar-left">
        <h2 class="header-content">РЕДАКТИРОВАТЬ альбом</h2>
    </div>
    <div class="top-bar-right">
    </div>
</div>
@endsection

@section('content')

{{ Form::model($album, ['route' => ['albums.update', $album->id], 'data-abide', 'novalidate']) }}
{{ method_field('PATCH') }}

@include('albums.form', ['submit_text' => 'Редактировать'])

{{ Form::close() }}

@endsection

@section('scripts')
@include('includes.scripts.inputs-mask')
@include('albums.scripts')
{{-- Проверка поля на существование --}}
@include('includes.scripts.check', ['entity' => 'albums'])
@endsection